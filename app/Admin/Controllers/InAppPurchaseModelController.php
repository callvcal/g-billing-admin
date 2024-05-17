<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\InAppPurchaseModel;

class InAppPurchaseModelController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'InAppPurchaseModel';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new InAppPurchaseModel());
        $grid->model()->orderBy('id',"DESC");

        $grid->column('id', __('Id'));
        $grid->column('user_id', __('User id'));
        $grid->column('location_id', __('Location id'));
        $grid->column('json', __('Json'));
        $grid->column('status', __('Status'));
        $grid->column('product_id', __('Product id'));
        $grid->column('purchase_id', __('Purchase id'));
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
        $show = new Show(InAppPurchaseModel::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('location_id', __('Location id'));
        $show->field('json', __('Json'));
        $show->field('status', __('Status'));
        $show->field('product_id', __('Product id'));
        $show->field('purchase_id', __('Purchase id'));
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
        $form = new Form(new InAppPurchaseModel());

        $form->number('user_id', __('User id'));
        $form->number('location_id', __('Location id'));
        $form->text('json', __('Json'));
        $form->text('status', __('Status'));
        $form->text('product_id', __('Product id'));
        $form->text('purchase_id', __('Purchase id'));

        return $form;
    }
}
