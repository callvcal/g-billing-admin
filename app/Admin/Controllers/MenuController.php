<?php

namespace App\Admin\Controllers;

use App\Models\Category;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Menu;
use App\Models\SubCategory;
use App\Models\Unit;
use OpenAdmin\Admin\Facades\Admin;

class MenuController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Menu';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Menu());
        $grid->enableHotKeys();
        //(new RelationController())->gridActions($grid);
        $states = [
            'on' => ['value' => 1, 'text' => 'open', 'color' => 'primary'],
            'off' => ['value' => 2, 'text' => 'close', 'color' => 'default'],
        ];
        $grid->column('active', __('Active'))->display(function ($v)  {
            return ($v==1)?'active':'inactive';
        });
        $grid->column('stocks', __('Stock'));
        $grid->column('alert_stocks', __('Alert'));
        $grid->expandFilter();
        $grid->filter(function ($filter) {
            
            $filter->disableIdFilter();
            
            $filter->like('name', 'name');
            $filter->equal('subcategory_id')->select((new HomeController())->query(SubCategory::class)->get()->pluck('name','id'));

            
        });

        $grid->column('id', __('Id'))->sortable();
        $grid->column('name', __('Name'))->sortable();
        $grid->column('image', __('Image'))->image("", 64, 64);
        $grid->column('price', __('Counter Price'))->sortable();
        $grid->column('price_din_in', __('Din in Price'))->sortable();
        $grid->column('price_with_delivery', __('Price with delivery'))->sortable();
        $grid->column('price_take_away', __('Price take away'))->sortable();
        (new RelationController())->gridSubCategory($grid);



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
        $show = new Show(Menu::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('image', __('Image'))->image();
        $show->field('subtitle', __('Subtitle'));
        $show->field('description', __('Description'));
        $show->field('price', __('Price'));
        $show->field('discount', __('Discount'));
        $show->field('serve_type', __('Serve type'));
        $show->field('food_type', __('Food type'));
        $show->field('category_id', __('Category id'));
        $show->field('subcategory_id', __('Subcategory id'));
        $show->field('unit_id', __('Unit id'));
        $show->field('kitchen_id', __('Kitchen id'));
        $show->field('admin_id', __('Admin id'));
        $show->field('ratings', __('Ratings'));
        $show->field('sells', __('Sells'));
        $show->field('stocks', __('Stocks'));
        $show->field('alert_stock', __('Alert stock'));
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
        $form = new Form(new Menu());

        $form->text('name', __('Enter item Name'))->required();
        $form->image('image', __('Select Item photo'))->required();
        // $form->text('subtitle', __('Enter Small description of item'));
        $form->textarea('description', __('Enter Description'))->required();
        $form->currency('price', __('Enter normal price'))->symbol("₹")->required();
        $form->currency('price_din_in', __('Enter Din in price (For app)'))->symbol("₹");
        $form->currency('price_with_delivery', __('Enter price with delivery (For app)'))->symbol("₹");
        $form->currency('price_take_away', __('Enter Take away price (For app)'))->symbol("₹");
        $form->number('discount', __('Enter discount in (%)'))->default(0)->required();
        $form->select('food_type', __('Food type'))->options(["veg" => "Veg", "non-veg" => "Non-Veg", "vegan" => "Vegan", "egg" => "Egg"])->required();

        $form->select('category_id', __('Select Category'))->options((new HomeController())->query(Category::class)->get()->pluck('name', 'id'))->required()->load('subcategory_id', '/api/load-subcategories');
        $form->select('subcategory_id', __('Select Sub-Category'))->options((new HomeController())->query(SubCategory::class)->get()->pluck("name", "id"))->required();

        $form->select('unit_id', __('Qty Unit'))->options(Unit::all()->pluck("name", "id"))->required();
        $form->text('qty', __('Enter quantity'))->default(1);

        $form->switch('active', __('Active'))->default(1);
        
        $form->text('calories_count', __('Calories count ,(Enter unit also)'))->default(0);
        $form->number('stocks', __('In Stocks'))->default(0);
        $form->number('alert_stocks', __('Alert Stocks'))->default(0);
        $form->text('weight_per_serving', __('Weight per serving ,(Enter unit also)'))->default(0);
        $form->text('proteins_count', __('Proteins count ,(Enter unit also)'))->default(0);
        $form->hidden('admin_id', __('Admin id'))->default(Admin::user()->id);
        $form->hidden('business_id', __('Business id'))->default(Admin::user()->business_id);
        return $form;
    }
}
