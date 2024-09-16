<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\SellItem;
use OpenAdmin\Admin\Facades\Admin;

class SellItemController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'SellItem';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SellItem());

        $grid->column('id', __('Id'))->sortable();
        $grid->column('date_time', __('Date time'))->sortable();
        $grid->column('user_id', __('User id'))->sortable();
        $grid->column('address_id', __('Address id'));
        $grid->column('admin_id', __('Admin id'));
        $grid->column('menu_id', __('Item id'));
        $grid->column('sell_id', __('Sell id'));
        $grid->column('qty', __('Qty'));
        $grid->column('discount_amt', __('Discount amt'));
        $grid->column('total_amt', __('Total amt'));
        $grid->column('gst_amt', __('Gst amt'));
        

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
        $show = new Show(SellItem::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('date_time', __('Date time'));
        $show->field('user_id', __('User id'));
        $show->field('address_id', __('Address id'));
        $show->field('admin_id', __('Admin id'));
        $show->field('menu_id', __('Item id'));
        $show->field('sell_id', __('Sell id'));
        $show->field('qty', __('Qty'));
        $show->field('discount_amt', __('Discount amt'));
        $show->field('total_amt', __('Total amt'));
        $show->field('gst_amt', __('Gst amt'));
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
        $form = new Form(new SellItem());

        $form->datetime('date_time', __('Date time'))->default(date('Y-m-d H:i:s'));
        $form->number('user_id', __('User id'));
        $form->number('address_id', __('Address id'));
        $form->number('admin_id', __('Admin id'));
        $form->number('menu_id', __('Item id'));
        $form->number('sell_id', __('Sell id'));
        $form->number('qty', __('Qty'));
        $form->number('discount_amt', __('Discount amt'));
        $form->number('total_amt', __('Total amt'));
        $form->number('gst_amt', __('Gst amt'));
        $form->hidden('admin_id', __('Admin id'))->default(Admin::user()->id);
        
        return $form;
    }
}
