<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\OrderStatusUpdate;
use OpenAdmin\Admin\Facades\Admin;

class OrderStatusUpdateController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'OrderStatusUpdate';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new OrderStatusUpdate());

        $grid->column('id', __('Id'));
        $grid->column('sell_id', __('Order id'));
        $grid->column('driver_id', __('Driver id'));
        $grid->column('user_id', __('User id'));
        $grid->column('order_status', __('Status'));
        $grid->column('delivery_status', __('Status'));
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
        $show = new Show(OrderStatusUpdate::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('sell_id', __('Order id'));
        $show->field('driver_id', __('Driver id'));
        $show->field('user_id', __('User id'));
        $show->field('order_status', __('Status'));
        $show->field('delivery_status', __('Status'));
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
        $form = new Form(new OrderStatusUpdate());

        $form->number('sell_id', __('Order id'));
        $form->number('driver_id', __('Driver id'));
        $form->number('user_id', __('User id'));
        $form->text('status', __('Status'));
        $form->hidden('admin_id', __('Admin id'))->default(Admin::user()->id);
        
        return $form;
    }
}
