<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\CustomerEvent;
use OpenAdmin\Admin\Facades\Admin;

class CustomerEventController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'CustomerEvent';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CustomerEvent());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('mobile', __('Mobile'));
        $grid->column('date', __('Date'));
        $grid->column('type', __('Type'));
        $grid->column('message', __('Message'));
        $grid->column('admin_id', __('Admin id'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(CustomerEvent::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('mobile', __('Mobile'));
        $show->field('date', __('Date'));
        $show->field('type', __('Type'));
        $show->field('message', __('Message'));
        $show->field('admin_id', __('Admin id'));
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
        $form = new Form(new CustomerEvent());

        $form->text('name', __('Name'));
        $form->phonenumber('mobile', __('Mobile'));
        $form->date('date', __('Date'))->default(date('Y-m-d'));
        $form->select('type', __('Type'))->options([
            'date_birth'=>'Birth Date',
            'anniversary_date'=>'Anniversary Date',
            'other'=>'Other',
        ]);
        $form->textarea('message', __('Message'));
        $form->hidden('admin_id', __('Admin id'))->default(Admin::user()->id);

        return $form;
    }
}
