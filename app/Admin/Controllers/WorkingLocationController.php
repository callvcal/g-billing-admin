<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\WorkingLocation;
use OpenAdmin\Admin\Facades\Admin;

class WorkingLocationController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Working Location';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new WorkingLocation());
        //(new RelationController())->gridActions($grid);

        $grid->column('id', __('Id'))->sortable();
        $grid->column('country', __('Country'))->sortable();
        $grid->column('state', __('State'))->sortable();
        $grid->column('district', __('District'))->sortable();
        $grid->column('city', __('City'))->sortable();
        $grid->column('pincode', __('Pincode'))->sortable();
        

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
        $show = new Show(WorkingLocation::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('country', __('Country'));
        $show->field('state', __('State'));
        $show->field('district', __('District'));
        $show->field('city', __('City'));
        $show->field('pincode', __('Pincode'));
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
        $form = new Form(new WorkingLocation());

        $form->text('country', __('Country'))->default('INDIA');
        $form->text('state', __('State'));
        $form->text('district', __('District'));
        $form->text('city', __('City'));
        $form->number('pincode', __('Pincode'));
        $form->hidden('admin_id', __('Admin id'))->default(Admin::user()->id);
        $form->hidden('business_id', __('Business id'))->default(Admin::user()->business_id);
        return $form;
    }
}
