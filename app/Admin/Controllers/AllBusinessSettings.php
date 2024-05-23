<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Setting;
use OpenAdmin\Admin\Facades\Admin;

class AllBusinessSetings extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'All Settings';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Setting());
        $grid->enableHotKeys();
        (new RelationController())->gridActions($grid);
        
        
        $grid->column('id', __('Id'))->sortable();
        $grid->column('admin_id', __('Admin ID'))->sortable();
        $grid->column('business_id', __('Business ID'))->sortable();
        $grid->business()->display(function ($model)  {
            if(!$model){
                return '';
            }
            return $model->name;
        });

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
        $show = new Show(Setting::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('admin_id', __('Bdmin id'));
        $show->field('admin_id', __('Business id'));
        $show->business()->display(function ($model)  {
            $model->setResource('/admin/businesses');

            $model->id();
            $model->name();
            $model->plan();
            $model->admin_id();
        });
        $show->field('json', __('Data'))->json();
        $show->field('business_id', __('Created at'));
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
        $form = new Form(new Setting());

        $form->text('name', __('Name'));
        $form->hidden('admin_id', __('Admin id'))->default(Admin::user()->id);
        $form->hidden('business_id', __('Business id'))->default(Admin::user()->business_id);
        return $form;
    }
}
