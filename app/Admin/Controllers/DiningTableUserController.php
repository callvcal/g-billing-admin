<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\DiningTableUser;

class DiningTableUserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'DiningTableUser';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DiningTableUser());

        $grid->column('id', __('Id'));
        $grid->column('dining_table_id', __('Dining table id'));
        $grid->column('date_time', __('Date time'));
        $grid->column('user_name', __('User name'));
        $grid->column('user_mobile', __('User mobile'));
        $grid->column('user_id', __('User id'));
        $grid->column('dining_table_request_id', __('Dining table request id'));
        $grid->column('amount', __('Amount'));
        $grid->column('invoice_id', __('Invoice id'));
        $grid->column('staff_user_id', __('Staff user id'));
        $grid->column('discount', __('Discount'));
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
        $show = new Show(DiningTableUser::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('dining_table_id', __('Dining table id'));
        $show->field('date_time', __('Date time'));
        $show->field('user_name', __('User name'));
        $show->field('user_mobile', __('User mobile'));
        $show->field('user_id', __('User id'));
        $show->field('dining_table_request_id', __('Dining table request id'));
        $show->field('amount', __('Amount'));
        $show->field('invoice_id', __('Invoice id'));
        $show->field('staff_user_id', __('Staff user id'));
        $show->field('discount', __('Discount'));
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
        $form = new Form(new DiningTableUser());

        $form->number('dining_table_id', __('Dining table id'));
        $form->datetime('date_time', __('Date time'))->default(date('Y-m-d H:i:s'));
        $form->text('user_name', __('User name'));
        $form->text('user_mobile', __('User mobile'));
        $form->number('user_id', __('User id'));
        $form->number('dining_table_request_id', __('Dining table request id'));
        $form->number('amount', __('Amount'));
        $form->number('invoice_id', __('Invoice id'));
        $form->number('staff_user_id', __('Staff user id'));
        $form->number('discount', __('Discount'));

        return $form;
    }
}
