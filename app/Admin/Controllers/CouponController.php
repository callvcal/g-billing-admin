<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Coupon;
use OpenAdmin\Admin\Facades\Admin;

class CouponController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Coupon';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Coupon());
        $grid->model()->orderBy('updated_at',"desc");
        $grid->enableHotKeys();
        (new RelationController())->gridActions($grid);

        $grid->column('id', __('Id'))->sortable();
        $grid->column('name', __('Name'))->sortable();
        $grid->column('image', __('Image'))->sortable();
        $grid->column('code', __('Code'))->sortable();
        $grid->column('discount', __('Discount'))->sortable();
        $grid->column('start_date', __('Start date'))->sortable();
        $grid->column('end_date', __('End date'))->sortable();
        

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
        $show = new Show(Coupon::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('image', __('Image'));
        $show->field('description', __('Description'));
        $show->field('code', __('Code'));
        $show->field('discount', __('Discount'));
        $show->field('max_uses', __('Max uses'));
        $show->field('min_amount', __('Min amount'));
        $show->field('uses', __('Uses'));
        $show->field('start_date', __('Start date'));
        $show->field('end_date', __('End date'));
        $show->field('admin_id', __('Admin id'));
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
        $form = new Form(new Coupon());

        $form->text('name', __('Name'))->required();
        $form->image('image', __('Image'));
        $form->text('description', __('Description'))->required();
        $form->text('code', __('Code'))->required();
        $form->number('discount', __('Discount'))->required();
        $form->number('max_uses', __('Max uses Per user (-1 for unlimited)'))->default(2);
        $form->number('min_amount', __('Min Amount'))->default(0);
        $form->datetime('start_date', __('Start date'))->default(date('Y-m-d H:i:s'))->required();
        $form->datetime('end_date', __('End date'))->default(date('Y-m-d H:i:s'))->required();
        $form->hidden('admin_id', __('Admin id'))->default(Admin::user()->id);
        $form->hidden('business_id', __('Business id'))->default(Admin::user()->business_id);
        return $form;
    }
}
