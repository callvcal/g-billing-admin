<?php

namespace App\Admin\Controllers;

use App\Models\Menu;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\MenuStock;
use OpenAdmin\Admin\Facades\Admin;

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


        $grid->model()->orderBy('id','DESC');
        $grid->menu()->display(function ($model) {
            if ($model == null) {
                return '<span class="badge rounded-pill bg-success">' . "N/A" . '</span> ';
            }
            return '<span class="badge rounded-pill bg-success">' . $model['name'] . '</span> ';
        });
        $grid->column('qty', __('Qty'));
        $grid->column('note', __('Note'));
        $grid->column('type', __('Type'));
        $grid->column('stock', __('Current Stock'));
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

        $form->select('menu_id', __('Item'))->options(Menu::all()->pluck('name', 'id'))->required();
        $form->number('qty', __('Qty'))->required();
        $form->textarea('note', __('Note'));
        $form->select('type', __('Type'))->options([
            'add' => 'Add Stock',
            'reduce' => 'Reduce Stock',
        ])->required();
        $form->hidden('stock', __('Stock'));
        $form->datetime('datetime', __('Datetime'))->default(date('Y-m-d H:i:s'));
        $form->hidden('admin_id', __('Admin id'))->default(Admin::user()->id);

        $form->saving(function (Form $form) {

            $type = $form->type;
            if ($type == 'STOCK-IN') {
                $form->qty = abs($form->qty);
            } else {
                $form->qty = -abs($form->qty);
            }
            $menu = Menu::find($form->menu_id);
            $form->stock = $form->qty + ($menu->stocks ?? 1);

            $menu->stocks=$form->stock;
            $menu->save();

        });

        return $form;
    }
}
