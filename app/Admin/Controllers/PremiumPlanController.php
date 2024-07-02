<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\PremiumPlan;

class PremiumPlanController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'PremiumPlan';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PremiumPlan());

        $grid->column('id', __('Id'))->sortable();
        $grid->column('active', __('Active'))->sortable();
        $grid->column('name', __('Name'))->sortable();
        $grid->column('key', __('Key'))->sortable();
        $grid->column('android_key', __('Android key'))->sortable();
        $grid->column('ios_key', __('Ios key'))->sortable();
        $grid->column('charge', __('Charge'))->sortable();
       
        $grid->column('created_at', __('Created at'))->sortable();
        $grid->column('updated_at', __('Updated at'))->sortable();

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
        $show = new Show(PremiumPlan::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('active', __('Active'));
        $show->field('name', __('Name'));
        $show->field('key', __('Key'));
        $show->field('android_key', __('Android key'));
        $show->field('ios_key', __('Ios key'));
        $show->field('features', __('Features'));
        $show->field('description', __('Description'));
        $show->field('days', __('Days'));
        $show->field('months', __('Months'));
        $show->field('years', __('Years'));
        $show->field('charge', __('Charge'));
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
        $form = new Form(new PremiumPlan());

        $form->switch('active', __('Active'))->default(1);
        $form->text('name', __('Name'))->required();
        $form->text('key', __('Key'))->required();
        $form->text('android_key', __('Android key'))->required();
        $form->text('ios_key', __('Ios key'))->required();
        $form->table('features', function ($table) {
            $table->text('color');
            $table->icon('icon');
            $table->text('text');
        });
        $form->text('description', __('Description'));
        $form->number('charge', __('Charge'))->default(0)->required();
        $form->number('days', __('Days'))->default(0)->required();
        $form->number('months', __('Months'))->default(0)->required();
        $form->number('years', __('Years'))->default(0)->required();

        return $form;
    }
}
