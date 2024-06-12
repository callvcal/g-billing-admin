<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\MenuStock;

class MenuStockController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'MenuStock';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new MenuStock());

        $grid->column('id', __('Id'));
        $grid->column('menu_id', __('Menu id'));
        $grid->column('qty', __('Qty'));
        $grid->column('note', __('Note'));
        $grid->column('type', __('Type'));
        $grid->column('stock', __('Stock'));
        $grid->column('datetime', __('Datetime'));
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
        $show = new Show(MenuStock::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('menu_id', __('Menu id'));
        $show->field('qty', __('Qty'));
        $show->field('note', __('Note'));
        $show->field('type', __('Type'));
        $show->field('stock', __('Stock'));
        $show->field('datetime', __('Datetime'));
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
        $form = new Form(new MenuStock());

        $form->number('menu_id', __('Menu id'));
        $form->number('qty', __('Qty'));
        $form->textarea('note', __('Note'));
        $form->text('type', __('Type'));
        $form->number('stock', __('Stock'));
        $form->datetime('datetime', __('Datetime'))->default(date('Y-m-d H:i:s'));

        return $form;
    }
}
