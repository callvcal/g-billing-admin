<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\OfflineTransaction;
use OpenAdmin\Admin\Facades\Admin;

class ExpenseController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Expenses';
    protected $type = 'expense';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new OfflineTransaction());
        $grid->quickCreate(function (Grid\Tools\QuickCreate $form) {
            $form->number('amount', __('Amount'));
            $form->text('cause', __('cause'));
            $form->hidden('type', __('e'))->default($this->type);
            $form->hidden('admin_id', __('Admin id'))->default(Admin::user()->id);
        });
        $grid->model()->where('type',$this->type);
        $grid->model()->orderBy('id',"desc");
        $grid->filter(function($filter){
            $filter->disableIdFilter();
            $filter->like('created_at')->date();
            $filter->like('updated_at')->date();
        });
        $grid->column('id', __('Id'))->sortable();
        $grid->column('amount', __('Amount'))->sortable();
        $grid->column('cause', __('Cause'))->sortable();
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
        $show = new Show(OfflineTransaction::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('amount', __('Amount'));
        $show->field('cause', __('Cause'));
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
        $form = new Form(new OfflineTransaction());

        $form->number('amount', __('Amount'));
        $form->hidden('type', __('Type'))->default($this->type);
        $form->textarea('cause', __('Cause'));
        $form->hidden('admin_id', __('Admin id'))->default(Admin::user()->id);
        return $form;
    }
}
