<?php

namespace App\Admin\Controllers;

use App\Models\AdminUser;
use App\Models\Business;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use OpenAdmin\Admin\Facades\Admin;

class StaffController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Staff';



    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $userModel = config('admin.database.users_model');

        $grid = new Grid(new $userModel());
        if (!isAdministrator()) {
            $grid->model()->whereHas('roles', function ($query) {
                $query->where('slug','!=','Partner-Admin')->where('slug','!=','Administrator');
            });
        }
        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('created_at')->date();
            $filter->like('username');
            $filter->like('email');
            $filter->like('name');
            if(isAdministrator()){
            $filter->like('business_key');
            $filter->equal('business_id')->select(Business::all()->pluck('name','id'));
            }
        });
        $grid->column('id', 'ID')->sortable();
        $grid->column('email', "Email");
        $grid->column('username', trans('admin.username'));
        $grid->column('name', trans('admin.name'));
        $grid->column('roles', trans('admin.roles'))->pluck('name')->label();
        $grid->column('created_at', trans('admin.created_at'));
        $grid->column('updated_at', trans('admin.updated_at'));




        $grid->tools(function (Grid\Tools $tools) {
            $tools->batch(function (Grid\Tools\BatchActions $actions) {
                $actions->disableDelete();
            });
        });



        $grid->actions(function (Grid\Displayers\Actions\Actions $actions) {

            if (!isAdministrator()) {
                if (checkRole($actions->getKey(), 'Partner-Admin')) {
                    $actions->disableDelete();
                }
                if (!is('Partner-Admin')) {
                    $actions->disableShow();
                    $actions->disableEdit();
                }
            }
        });


        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        $userModel = config('admin.database.users_model');

        $show = new Show($userModel::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('username', trans('admin.username'));
        $show->field('name', trans('admin.name'));
        $show->field('roles', trans('admin.roles'))->as(function ($roles) {
            return $roles->pluck('name');
        })->label();
        $show->field('permissions', trans('admin.permissions'))->as(function ($permission) {
            return $permission->pluck('name');
        })->label();
        $show->field('created_at', trans('admin.created_at'));
        $show->field('updated_at', trans('admin.updated_at'));

        return $show;
    }


    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $userModel = config('admin.database.users_model');
        $permissionModel = config('admin.database.permissions_model');
        $roleModel = config('admin.database.roles_model');


        $model=new $userModel();
        $form = new Form($model);

        $userTable = config('admin.database.users_table');
        $connection = config('admin.database.connection');

        $form->display('id', 'ID');


        if (isAdministrator()) {
            $form->text('username', trans('admin.username'))

                ->creationRules(['required', "unique:{$connection}.{$userTable}"])
                ->updateRules(['required', "unique:{$connection}.{$userTable},username,{{id}}"]);
        } else {
            $business = Admin::user()->business;
            $pattern = '/^.{2,8}@' . preg_quote($business, '/') . '$/';
            $form->text('username', trans('admin.username'))
                ->rules(
                    'required|regex:' . $pattern . '|unique:admin_users,username,{{id}}',
                    [
                        'regex' => 'The code format is invalid. It must be in the format [anything, min 2, max 8]@' . $business,
                        'unique' => "There is already user with this username",
                        'required' => "required",
                    ]
                )
                // ->creationRules(['required', "unique:{$connection}.{$userTable}"])
                // ->updateRules(['required', "unique:{$connection}.{$userTable},username,{{id}}"])
                ->default("staff@$business")
                ->help("The username must be in the format [anything, min 2, max 8]@$business.");
        }





        $form->text('name', __('Name'))->required();
        $form->email('email', __('Email'));
        $form->password('password', trans('admin.password'))->rules('required|confirmed');
        $form->password('password_confirmation', trans('admin.password_confirmation'))->rules('required')
            ->default(function ($form) {
                return $form->model()->password;
            });



        $form->ignore(['password_confirmation']);
        $form->phonenumber('mobile', __('Mobile'));
        $form->image('avatar', trans('admin.avatar'));

        if (isAdministrator()) {
            $form->select('business_id', __('Business id'))->options(Business::all()->pluck('name', 'id'));
            $form->select('business_key', __('Business name'))->options(Business::all()->pluck('name', 'name'));
            $form->select('admin_id', __('Admin id'))->options(AdminUser::all()->pluck('name', 'id'));
        } else {
            $form->hidden('business_id', __('Business id'))->default(Admin::user()->business_id);
            $form->hidden('business_key', __('Business id'))->default(Admin::user()->business);
            $form->hidden('admin_id', __('Admin id'))->default(Admin::user()->id);
        }

        if (isAdministrator()) {
            $form->multipleSelect('roles', trans('admin.roles'))->options($roleModel::where('slug', 'like', 'Partner-%')->pluck('name', 'id'));
        } else

        // if (isOwner()) {
        //     $form->multipleSelect('roles', trans('admin.roles'))->options($roleModel::where('slug', 'like', 'Partner-%')->where('slug', '!=', 'Partner-Admin')->pluck('name', 'id'));
        // } else {
        //     $form->multipleSelect('roles', trans('admin.roles'))->options($roleModel::where('slug', 'like', 'Partner-%')->where('slug', '!=', 'Partner-Admin')->pluck('name', 'id'));
        // }
        $form->multipleSelect('roles', trans('admin.roles'))->options($roleModel::where('slug', 'like', 'Partner-%')->where('slug', '!=', 'Partner-Admin')->pluck('name', 'id'));





        $form->saving(function (Form $form) {
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = Hash::make($form->password);
            }
            if ($form->username && $form->model()->username != $form->username) {
                // $form->username = Hash::make($form->password);
            }
        });



        return $form;
    }
}
