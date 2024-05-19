<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Material;
use App\Models\Unit;
use OpenAdmin\Admin\Facades\Admin;

class MaterialController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Material';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Material());

        $grid->column('id', __('Id'))->sortable();
        $grid->column('name', __('Name'))->sortable();
        $grid->transactions()->display(function ($transactions) {

            if ($transactions == null) {
                return '<span class="badge rounded-pill bg-success">' . "0" . '</span> ';
            }
            $stockIn=array_filter($transactions,function ($item)  {
                return $item['type']=='stock-in';
            });
            $stockOut=array_filter($transactions,function ($item)  {
                return $item['type']=='stock-out';
            });

            $stockInQty=array_reduce($stockIn,function ($carry,$item)  {
                return $carry+ ($item['qty']);
            });
            $stockOutQty=array_reduce($stockOut,function ($carry,$item)  {
                return $carry+ ($item['qty']);
            });



            return '<span class="badge rounded-pill bg-success">' .  ($stockInQty-$stockOutQty) . '</span> ';
        })->sortable();
        
        
        $grid->quickCreate(function (Grid\Tools\QuickCreate $form) {
            $form->text('name', __('Name'));
            $form->hidden('business_id', __('Business id'))->default(Admin::user()->business_id);
            $form->hidden('admin_id', __('Admin id'))->default(Admin::user()->id);
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
        $show = new Show(Material::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('admin_id', __('Admin id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->transactions('Transactions', function ($items) {

            $items->setResource('/admin/raw-matrials');
            $items->qty();
            // $items->amount();
            $items->datetime();
            $items->type();
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
        $form = new Form(new Material());

        $form->text('name', __('Name'));
        $form->hidden('admin_id', __('Admin id'))->default(Admin::user()->id);
        $form->hidden('admin_id', __('Admin id'))->default(Admin::user()->id);
        $form->hidden('business_id', __('Business id'))->default(Admin::user()->business_id);
        return $form;
    }
}
