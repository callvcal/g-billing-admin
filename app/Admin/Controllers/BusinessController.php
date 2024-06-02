<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\LaunchPartner;
use App\Models\AdminUser;
use App\Models\Business;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use App\Models\User;

class BusinessController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Businesses';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Business());

        $grid->model()->orderBy('id', "DESC");
        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('plan', __('Plan'));
        $grid->column('active', __('active'))->switch();
        // $grid->column('deleted', __('deleted'))->switch();
        $grid->column('on_board_way', __('On board way'));
        $grid->column('on_board_date', __('On board date'));
        $grid->column('purchase_date', __('Purchase date'));
        $grid->column('last_subscription_date', __('Last subscription date'));
        $grid->column('expiry_date', __('Expiry Date'));
        $grid->column('deleting_date', __('Deleted Date'));
        $grid->column('admin', __('Admin Mobile'))->display(function ($model) {

            if ($model == null || $model['mobile'] == null) {
                return "N/A";
            }
            return $model['mobile'];
        })->sortable();
        $grid->actions(function ($actions) {
            $actions->add(new LaunchPartner());
        });
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Business::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('admin_id', __('User admin id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('deleting_date', __('deleted at'));
        $show->admin('Admin information', function ($model) {
            $model->setResource('/admin/auth/users');
            $model->id();
            $model->name();
            $model->mobile();
            $model->email();
            $model->business_key();
        });
        // $show->admin()->json();
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Business());

        $form->text('name', __('Name'));
        $form->select('admin_id', __('User admin id'))->options(AdminUser::all()->pluck('name', 'id'));
        $form->select('plan', __('plan'))->options(['free' => 'Free', 'monthly' => "Monthly", 'annual' => "Annual", 'lifetime' => "10 years"]);
        $form->select('on_board_way', __('On board way'))->options(['app' => 'App', 'offline' => "Offline"]);
        $form->date('on_board_date', __('On board date'));
        $form->date('purchase_date', __('Purchase date'));
        $form->date('expiry_date', __('Expiry date'));
        $form->date('last_subscription_date', __('Last subscription date'));
        $form->datetime('deleting_date', __('Deleted date'));
        $form->switch('active', __('status'));
        $form->switch('deleted', __('Deleted'));

        return $form;
    }
}
