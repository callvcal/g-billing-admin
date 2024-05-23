<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Setting;
use OpenAdmin\Admin\Facades\Admin;

class AllBusinessSettings extends AdminController
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

        $grid->disableCreateButton();

        $grid->actions(function (Grid\Displayers\Actions\Actions $actions) {
            $actions->disableDelete();
            $actions->disableEdit();
        });

        $grid->tools(function (Grid\Tools $tools) {
            $tools->batch(function (Grid\Tools\BatchActions $actions) {
                $actions->disableDelete();
                $actions->disableEdit();
            });
        });

        $grid->column('id', __('Business ID'))->sortable();
        $grid->column('admin_id', __('Admin ID'))->sortable();
        $grid->business()->display(function ($model) {
            if ($model==null) {
                return '';
            }
            return $model['name'];
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

        $show->field('id', __('Business ID'));
       
        
        
        
        $show->field('json', __('Data'))->json();
        $show->panel()
            ->tools(function ($tools) {

                $tools->disableEdit();

                $tools->disableDelete();
            });
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
        
        return $form;
    }
}
