<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\AdsBanner;
use App\Models\Menu;
use App\Models\SubCategory;

class AdsBannerController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'AdsBanner';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new AdsBanner());
        // $grid->quickCreate(function (Grid\Tools\QuickCreate $form) {
        //     $form->text('title', __('Title'))->required();
        //     $form->text('description', __('Description'))->required();
        //     $form->image('image', __('Image'))->required();
        //     $form->select('menu_id', __('Select menu'))->options(Menu::all()->pluck("name", "id"));
        //     $form->select('banner_type', __('Select banner type'))->options([
        //         'small' => "Small Banner Ad",
        //         'normal' => "Normal Banner Ad",
        //         'dining_table' => "Dining Table",
        //     ]);
        // });
        $grid->model()->orderBy('updated_at', "desc");
        $grid->column('image', __('Image'))->image("", width: 64, height: 64);
        $grid->column('title', __('Title'))->sortable();
        $grid->column('banner_type', __('banner_type'))->sortable();
        (new RelationController())->gridSubCategory($grid);
        (new RelationController())->gridActions($grid);

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
        $show = new Show(AdsBanner::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
        $show->field('description', __('Description'));
        $show->field('banner_type', __('Banner type'));
        $show->field('image', __('Image'))->image();
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        (new RelationController())->detailsSubCategory($show);

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new AdsBanner());

        $form->text('title', __('Title'))->required();
        $form->text('description', __('Description'))->required();
        $form->image('image', __('Image'))->required();
        $form->select('subcategory_id', __('Select Subcategory'))->options(SubCategory::all()->pluck("name", "id"));
        $form->select('banner_type', __('Select banner type'))->options([
            'small' => "Small Banner Ad",
            'normal' => "Normal Banner Ad",
            'dining_table' => "Dining Table",
        ]);

        return $form;
    }
}
