<?php

namespace App\Admin\Controllers;

use App\Models\AdminUser;
use App\Models\Business;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Category;
use App\Models\User;
use OpenAdmin\Admin\Facades\Admin;

class CategoryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Category';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Category());
        $grid->model()->orderBy('updated_at',"desc");
        $grid->enableHotKeys();
        //(new RelationController())->gridActions($grid);
        $grid->quickCreate(function (Grid\Tools\QuickCreate $form) {
            $form->text('name', __('Name'));
            // $form->image('image', __('Image'));
            // $form->image('web_image', __('Web Image'));
            $form->hidden('admin_id', __('Admin id'))->default(Admin::user()->id);
        });
        $grid->column('id', __('Id'))->sortable();
        $grid->column('name', __('Name'))->sortable();
        // $grid->column('image', __('Image'))->image("",64,64);
        // $grid->column('web_image', __('Web Image'))->image("",64,64);
        

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
        $show = new Show(Category::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        // $show->field('image', __('Image'))->image();
        // $show->field('web_image', __('Web Image'))->image();
        $show->field('admin_id', __('admin_id'));
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
        $form = new Form(new Category());

        $form->text('name', __('Name'));
        // $form->image('image', __('Image'));
        // $form->image('web_image', __('Web Image'));
        $form->hidden('admin_id', __('Admin id'))->default(Admin::user()->id);
        return $form;
    }
}
