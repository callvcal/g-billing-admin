<?php

namespace App\Admin\Controllers;

use App\Models\Material;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\RawMatrial;
use App\Models\Unit;
use OpenAdmin\Admin\Facades\Admin;

class RawMatrialController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'RawMatrial';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new RawMatrial());
        $grid->enableHotKeys();
        // $grid->quickCreate(function (Grid\Tools\QuickCreate $form) {
        //     $form->text('name', __('Name'))->required();
        //     $form->select('unit_id', __('Qty Unit'))->options(Unit::all()->pluck("name", "id"))->required();
        //     $form->hidden('admin_id', __('Admin id'))->default(Admin::user()->id);
        //     $form->hidden('datetime', __('Datetime'))->default(date('Y-m-d H:i:s'));
        //     $form->number('qty', __('Qty'))->required();
        //     // $form->decimal('amount', __('Amount'));
        //     $form->select('type', __('Type'))->options([
        //         'stock-in' => "Stock In",
        //         'stock-out' => "Stock Out",
        //     ])->required();
        // });
        $grid->column('id', __('Id'));
        // $grid->unit()->display(function ($model) {
        //     if (isset($model)) {
        //         return $model['name'];
        //     }
        //     return '';
        // });
        $grid->material()->display(function ($model) {
            if (isset($model)) {
                return $model['name'];
            }
            return '';
        });
        $grid->column('datetime', __('Datetime'));
        $grid->column('qty', __('Qty'));
        // $grid->column('amount', __('Amount'));
        $grid->column('type', __('Type'));

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
        $show = new Show(RawMatrial::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('unit_id', __('Unit id'));
        $show->field('admin_id', __('Admin id'));
        $show->field('datetime', __('Datetime'));
        $show->field('qty', __('Qty'));
        $show->field('amount', __('Amount'));
        $show->field('type', __('Type'));
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
        $form = new Form(new RawMatrial());

        $form->select('material_id', __('Material'))->options(Material::all()->pluck("name", "id"))->required();
        $form->hidden('admin_id', __('Admin id'))->default(Admin::user()->id);
        $form->datetime('datetime', __('Datetime'))->default(date('Y-m-d H:i:s'));
        $form->number('qty', __('Qty'))->required();
        $form->decimal('amount', __('Amount'));
        $form->select('type', __('Type'))->options([
            'stock-in' => "Stock In",
            'stock-out' => "Stock Out",
        ])->required();
        $form->hidden('admin_id', __('Admin id'))->default(Admin::user()->id);
        $form->hidden('business_id', __('Business id'))->default(Admin::user()->business_id);
        return $form;
    }
}
