<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\RecipeMaterial;

class RecipeMaterialController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'RecipeMaterial';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new RecipeMaterial());

        $grid->column('id', __('Id'));
        $grid->column('receipe_id', __('recipe id'));
        $grid->column('menu_id', __('Menu id'));
        $grid->column('admin_id', __('Admin id'));
        $grid->column('business_id', __('Business id'));
        $grid->column('material_id', __('Material id'));
        $grid->column('qty', __('Qty'));
        $grid->column('allow_dine_in', __('Allow dine in'));
        $grid->column('allow_parcel_delivery', __('Allow parcel delivery'));
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
        $show = new Show(RecipeMaterial::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('receipe_id', __('recipe id'));
        $show->field('menu_id', __('Menu id'));
        $show->field('admin_id', __('Admin id'));
        $show->field('business_id', __('Business id'));
        $show->field('material_id', __('Material id'));
        $show->field('qty', __('Qty'));
        $show->field('allow_dine_in', __('Allow dine in'));
        $show->field('allow_parcel_delivery', __('Allow parcel delivery'));
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
        $form = new Form(new RecipeMaterial());

        $form->number('receipe_id', __('recipe id'));
        $form->number('menu_id', __('Menu id'));
        $form->number('admin_id', __('Admin id'));
        $form->number('business_id', __('Business id'));
        $form->number('material_id', __('Material id'));
        $form->decimal('qty', __('Qty'));
        $form->switch('allow_dine_in', __('Allow dine in'));
        $form->switch('allow_parcel_delivery', __('Allow parcel delivery'));

        return $form;
    }
}
