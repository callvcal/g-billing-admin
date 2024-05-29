<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\ClientReview;

class ClientReviewController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'ClientReview';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ClientReview());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('address', __('Address'));
        $grid->column('city', __('City'));
        $grid->column('country', __('Country'));
        $grid->column('message', __('Message'));
        $grid->column('image', __('Image'));
        $grid->column('date', __('Date'));
        $grid->column('starts', __('Starts'));
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
        $show = new Show(ClientReview::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('address', __('Address'));
        $show->field('city', __('City'));
        $show->field('country', __('Country'));
        $show->field('message', __('Message'));
        $show->field('image', __('Image'));
        $show->field('date', __('Date'));
        $show->field('starts', __('Starts'));
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
        $form = new Form(new ClientReview());

        $form->text('name', __('Name'));
        $form->textarea('address', __('Address'));
        $form->text('city', __('City'));
        $form->text('country', __('Country'));
        $form->textarea('message', __('Message'));
        $form->image('image', __('Image'));
        $form->time('date', __('Date'))->default(date('H:i:s'));
        $form->number('starts', __('Starts'));

        return $form;
    }
}
