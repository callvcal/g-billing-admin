<?php

namespace App\Admin\Controllers;

use App\Models\Category;
use App\Models\Kitchen;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\SubCategory;
use OpenAdmin\Admin\Facades\Admin;

class SubCategoryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'SubCategory';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SubCategory());
        $grid->enableHotKeys();
        (new RelationController())->gridActions($grid);
        
        
        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('image', __('Image'))->image("",64,64);
        $grid->column('admin_id', __('Admin id'));
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
        $show = new Show(SubCategory::findOrFail($id));

        $show->field('id', __('Id'))->sortable();
        $show->field('name', __('Name'))->sortable();
        $show->field('image', __('Image'))->image();
        

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new SubCategory());

        $form->text('name', __('Name'));
        $form->image('image', __('Image'));
        $form->select('category_id', __('Select Category'))->options((new HomeController())->query(Category::class)->get()->pluck("name","id"));
        $form->select('kitchen_id', __('Select Kitchen'))->options((new HomeController())->query(Kitchen::class)->get()->pluck("name","id"));
        $form->hidden('admin_id', __('Admin id'))->default(Admin::user()->id);
        $form->hidden('business_id', __('Business id'))->default(Admin::user()->business_id);
        return $form;
    }
}
