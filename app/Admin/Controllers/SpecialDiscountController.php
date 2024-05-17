<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\SpecialDiscount;

class SpecialDiscountController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'SpecialDiscount';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SpecialDiscount());

        $grid->column('id', __('Id'));
        $grid->column('date_of_allocation', __('Date of allocation'));
        $grid->column('refering_user_id', __('Refering user id'));
        $grid->column('user_id', __('User id'));
        $grid->column('is_used', __('Is used'));
        $grid->column('is_valid', __('Is valid'));
        $grid->column('discount', __('Discount'));
        $grid->column('used_date', __('Used date'));
        $grid->column('sell_id', __('Sell id'));
        $grid->column('mobile', __('Mobile'));
        $grid->column('discount_index', __('Discount index'));
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
        $show = new Show(SpecialDiscount::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('date_of_allocation', __('Date of allocation'));
        $show->field('refering_user_id', __('Refering user id'));
        $show->field('user_id', __('User id'));
        $show->field('is_used', __('Is used'));
        $show->field('is_valid', __('Is valid'));
        $show->field('discount', __('Discount'));
        $show->field('used_date', __('Used date'));
        $show->field('sell_id', __('Sell id'));
        $show->field('mobile', __('Mobile'));
        $show->field('discount_index', __('Discount index'));
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
        $form = new Form(new SpecialDiscount());

        $form->text('date_of_allocation', __('Date of allocation'));
        $form->number('refering_user_id', __('Refering user id'));
        $form->number('user_id', __('User id'));
        $form->switch('is_used', __('Is used'));
        $form->switch('is_valid', __('Is valid'));
        $form->number('discount', __('Discount'));
        $form->datetime('used_date', __('Used date'))->default(date('Y-m-d H:i:s'));
        $form->number('sell_id', __('Sell id'));
        $form->phonenumber('mobile', __('Mobile'));
        $form->text('discount_index', __('Discount index'));

        return $form;
    }
}
