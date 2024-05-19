<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\DiningTable;
use OpenAdmin\Admin\Facades\Admin;

class DiningTableController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Dining Table';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DiningTable());
        $grid->model()->orderBy('updated_at', "desc");
        (new RelationController())->gridActions($grid);
        $grid->quickCreate(function (Grid\Tools\QuickCreate $form) {
            $form->text('name', __('Name'));
            $form->number('capacity', __('Capacity'));
            $form->text('number', __('Table Number'))->required();
            $form->hidden('business_id', __('Business id'))->default(Admin::user()->business_id);
            $form->hidden('admin_id', __('Admin id'))->default(Admin::user()->id);
           });
        $grid->column('id', __('Id'))->sortable();
        $grid->column('status', __('status'))->sortable();
        $grid->column('name', __('Name'))->sortable();
        $grid->column('capacity', __('Capacity'))->sortable();
        $grid->column('number', __('Number'))->sortable();


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
        $show = new Show(DiningTable::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        // $show->field('charge', __('Charge'));
        $show->field('capacity', __('Capacity'));
        $show->field('number', __('Number'));
        // $show->field('admin_id', __('Admin id'));
        // $show->field('staff_id', __('Staff id'));
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
        $form = new Form(new DiningTable());

        $form->text('name', __('Name'));
        $form->number('capacity', __('Capacity'));
        $form->select('status', __('status'))->options([
            'blank'=>"Blank",
            'running'=>"Running",
        ])->default('blank');
        $form->text('number', __('Table Number'))->required();
        $form->hidden('admin_id', __('Admin id'))->default(Admin::user()->id);
        $form->hidden('business_id', __('Business id'))->default(Admin::user()->business_id);
        return $form;
    }
}
