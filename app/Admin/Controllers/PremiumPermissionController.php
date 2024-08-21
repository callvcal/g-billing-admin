<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\PremiumPermission;
use OpenAdmin\Admin\Facades\Admin;

class PremiumPermissionController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'PremiumPermission';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PremiumPermission());

        $grid->column('id', __('Id'));
        $grid->column('active', __('Active'));
        $grid->column('name', __('Name'));
        $grid->column('slug', __('Slug'));
        $grid->column('color', __('Color'));
        $grid->column('icon', __('Icon'));
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
        $show = new Show(PremiumPermission::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('active', __('Active'));
        $show->field('name', __('Name'));
        $show->field('slug', __('Slug'));
        $show->field('color', __('Color'));
        $show->field('icon', __('Icon'));
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
        $form = new Form(new PremiumPermission());

        $form->switch('active', __('Active'));
        $form->text('name', __('Name'))->required();
        $form->text('slug', __('Slug'))->required();
        $form->color('color', __('Color'));
        $form->text('icon', __('Icon'));
        $form->hidden('admin_id', __('Admin id'))->default(Admin::user()->id);

        return $form;
    }
}
