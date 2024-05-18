<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Unit;
use OpenAdmin\Admin\Facades\Admin;

class UnitController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Unit';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Unit());
        $grid->enableHotKeys();
        (new RelationController())->gridActions($grid);
        $grid->quickCreate(function (Grid\Tools\QuickCreate $form) {
            $form->text('name', __('Name'));
            // $form->number('qty', __('Qty'));
        });
        $grid->column('id', __('Id'))->sortable();
        $grid->column('name', __('Name'))->sortable();
        // $grid->column('qty', __('Qty'))->sortable();


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
        $show = new Show(Unit::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('qty', __('Qty'));
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
        $form = new Form(new Unit());
        $form->text('name', __('Name'));
        $form->hidden('admin_id', __('Admin id'))->default(Admin::user()->id);
        $form->hidden('business_id', __('Business id'))->default(Admin::user()->business_id);
        return $form;
    }
}
