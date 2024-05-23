<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Setting;
use OpenAdmin\Admin\Facades\Admin;

class AllBusinessSettings extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'All Settings';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Setting());
        $grid->enableHotKeys();
        (new RelationController())->gridActions($grid);

        $grid->disableCreateButton();

        $grid->actions(function (Grid\Displayers\Actions\Actions $actions) {
            $actions->disableDelete();
            $actions->disableEdit();
        });

        $grid->tools(function (Grid\Tools $tools) {
            $tools->batch(function (Grid\Tools\BatchActions $actions) {
                $actions->disableDelete();
                $actions->disableEdit();
            });
        });

        $grid->column('id', __('Business ID'))->sortable();
        $grid->column('admin_id', __('Admin ID'))->sortable();
        $grid->business()->display(function ($model) {
            if ($model==null) {
                return '';
            }
            return $model['name'];
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
        $show = new Show(Setting::findOrFail($id));

        $show->field('id', __('Business ID'));
        $show->business("Business information",function ($model) {
            $model->setResource('/admin/businesses');
            $model->disableDelete();
            $model->disableEdit();

            $model->id();
            $model->name();
            $model->plan();
            $model->admin_id();
        });
        
        
        $show->field('json', __('Data'))->json();
        $show->panel()
            ->tools(function ($tools) {

                $tools->disableEdit();

                $tools->disableDelete();
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
        $form = new Form(new Setting());
        $form->text('shop_name', 'Shop Name')->rules('required');
        $form->text('address', 'Address')->rules('required');
        $form->text('mobile', 'Mobile Number')->rules('required');
        $form->email('email', 'Email Address')->rules('required|email');
        $form->url('map_link', 'Google Map Link')->rules('required');
        $form->text('latitude', 'Shop Location Latitude')->rules('required');
        $form->text('longitude', 'Shop Location  Longitude')->rules('required');

        $states = [
            0 => "No",
            1 => "Yes",
        ];

        $form->select('allow_breakfast', 'Allow Breakfast')->options($states)->default(1);
        $form->select('allow_lunch', 'Allow Lunch')->options($states)->default(1);
        $form->select('allow_dinner', 'Allow Dinner')->options($states)->default(1);
        $form->select('print_gst', 'Print GST')->options($states)->default(1);
        $form->select('is_gst_included', 'Is GST Included')->options($states)->default(1);
        $form->number('gst_rate', 'Enter GST Rate(0-100)%')->default(10)->rules('required');
        // $form->text('price_per_km', 'Delivery Charge Per KM')->rules('required');
        // $form->image('refer_earn_image', 'Refer & Earn Image');
        // $form->number('refer_earn_new_user_rewards', 'Refere & Earn New user rewards')->default(200)->rules('required');
        // $form->number('refer_earn_old_user_rewards', 'Refere & Earn Old user rewards')->default(0)->rules('required');
        // $form->number('refer_earn_referer_discount', 'Referer user discount (0-100)%')->default(10)->rules('required');
        // $form->number('refer_earn_refered_discount_first_order', 'Refered user discount for 1st order(0-100)%')->default(50)->rules('required');
        // $form->number('refer_earn_refered_discount_second_order', 'Refered user discount for 2nd order(0-100)%')->default(25)->rules('required');
        // $form->number('refer_earn_refered_discount_third_order', 'Refered user discount for 3rd order(0-100)%')->default(10)->rules('required');
        // $form->text('rewardConvertRate', 'Reward convert rate')->rules('required');
        $form->text('printer_network_address', 'Printer Network address');
        $form->text('printer_port', 'Printer Port');
        $form->text('footer_message', 'Footer Message')->default("Thank You");
        $form->text('gstin', 'GSTIN');
        $form->text('name', __('Name'));
        // $form->hidden('admin_id', __('Admin id'))->default(Admin::user()->id);
        // $form->hidden('business_id', __('Business id'))->default(Admin::user()->business_id);
        return $form;
    }
}
