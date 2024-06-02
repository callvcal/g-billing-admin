<?php

namespace App\Admin\Controllers;


use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\User;
use OpenAdmin\Admin\Facades\Admin;

class UserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Users';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());
        $grid->model()->orderBy('updated_at', "desc");
        //(new RelationController())->gridActions($grid);

        $grid->column('id', __('Id'))->sortable();
        $grid->column('image', __('Thumbnail'))->image("", 64, 64);
        $grid->column('name', __('Name'))->sortable();
        $grid->column('email', __('Email'))->sortable();
        $grid->column('mobile', __('Mobile'))->sortable();


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
        $show = new Show(User::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('email', __('Email'));
        $show->field('mobile', __('Mobile'));
        $show->field('image', __('Image'))->image();
        $show->field('date_of_birth', __('Date of birth'));
        $show->field('gender', __('Gender'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new User());

        $form->text('name', __('Name'));
        $form->email('email', __('Email'));
        $form->phonenumber('mobile', __('Mobile'));
        $form->image('image', __('Image'));
        $form->date('date_of_birth', __('Date of birth'))->default(date('Y-m-d'));
        $form->text('gender', __('Gender'));
        $form->hidden('admin_id', __('Admin id'))->default(Admin::user()->id);
        $form->hidden('business_id', __('Business id'))->default(Admin::user()->business_id);
        return $form;
    }
}
