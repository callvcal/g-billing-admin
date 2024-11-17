<?php

namespace App\Admin\Controllers;

use App\Models\Business;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Restorent;
use OpenAdmin\Admin\Facades\Admin;

class RestorentController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Restorent';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Restorent());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('image', __('Image'));
        $grid->column('address', __('Address'));
        $grid->column('subtitle', __('Subtitle'));
        $grid->column('restorent_types', __('Restorent types'));
        $grid->column('tags', __('Tags'));
        $grid->column('food_type', __('Food type'));
        $grid->column('serve_type', __('Serve type'));
        $grid->column('legan_name', __('Legan name'));
        $grid->column('gst_number', __('Gst number'));
        $grid->column('gst_file', __('Gst file'));
        $grid->column('fssai_lic_no', __('Fssai lic no'));
        $grid->column('fssai_lic_file', __('Fssai lic file'));
        $grid->column('fssai_lic_no_expiry', __('Fssai lic no expiry'));
        $grid->column('mobile', __('Mobile'));
        $grid->column('owner_name', __('Owner name'));
        $grid->column('latitude', __('Latitude'));
        $grid->column('longitude', __('Longitude'));
        $grid->column('allow_breakfast', __('Allow breakfast'));
        $grid->column('allow_lunch', __('Allow lunch'));
        $grid->column('allow_dinner', __('Allow dinner'));
        $grid->column('admin_id', __('Admin id'));
        $grid->column('business_id', __('Business id'));
        $grid->column('active', __('Active'));
        $grid->column('restorent_id', __('Restorent id'));
        $grid->column('is_verified', __('Is verified'));
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
        $show = new Show(Restorent::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('image', __('Image'));
        $show->field('address', __('Address'));
        $show->field('subtitle', __('Subtitle'));
        $show->field('restorent_types', __('Restorent types'));
        $show->field('tags', __('Tags'));
        $show->field('food_type', __('Food type'));
        $show->field('serve_type', __('Serve type'));
        $show->field('legan_name', __('Legan name'));
        $show->field('gst_number', __('Gst number'));
        $show->field('gst_file', __('Gst file'));
        $show->field('fssai_lic_no', __('Fssai lic no'));
        $show->field('fssai_lic_file', __('Fssai lic file'));
        $show->field('fssai_lic_no_expiry', __('Fssai lic no expiry'));
        $show->field('mobile', __('Mobile'));
        $show->field('owner_name', __('Owner name'));
        $show->field('latitude', __('Latitude'));
        $show->field('longitude', __('Longitude'));
        $show->field('allow_breakfast', __('Allow breakfast'));
        $show->field('allow_lunch', __('Allow lunch'));
        $show->field('allow_dinner', __('Allow dinner'));
        $show->field('admin_id', __('Admin id'));
        $show->field('business_id', __('Business id'));
        $show->field('active', __('Active'));
        $show->field('restorent_id', __('Restorent id'));
        $show->field('is_verified', __('Is verified'));
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
        $form = new Form(new Restorent());

        $form->text('name', __('Name'));
        $form->image('image', __('Image'));
        $form->text('address', __('Address'));
        $form->text('subtitle', __('Subtitle'));
        $form->text('restorent_types', __('Restorent types'));
        $form->tags('tags', __('Tags'));
        $form->text('food_type', __('Food type'))->options(["veg" => "Veg", "non-veg" => "Non-Veg", "vegan" => "Vegan", "egg" => "Egg"])->required();
        $form->text('serve_type', __('Serve type'));
        $form->text('legan_name', __('Legan name'));
        $form->text('gst_number', __('Gst number'));
        $form->file('gst_file', __('Gst file'));
        $form->text('fssai_lic_no', __('Fssai lic no'));
        $form->file('fssai_lic_file', __('Fssai lic file'));
        $form->date('fssai_lic_no_expiry', __('Fssai lic no expiry'))->default(date('Y-m-d'));
        $form->phonenumber('mobile', __('Mobile'));
        $form->text('owner_name', __('Owner name'));
        $form->decimal('latitude', __('Latitude'));
        $form->decimal('longitude', __('Longitude'));
        $form->switch('allow_breakfast', __('Allow breakfast'));
        $form->switch('allow_lunch', __('Allow lunch'));
        $form->switch('allow_dinner', __('Allow dinner'));
        $form->hidden('admin_id', __('Admin id'))->default(Admin::user()->id);
        if(isAdministrator()){
            $form->select('business_id', __('Business id'))->options(Business::all()->pluck('name','id'));

        }else{
        $form->hidden('business_id', __('Business id'))->default(Admin::user()->business_id);

        }
        
        $form->switch('active', __('Active'))->default(1);
        // $form->text('restorent_id', __('Restorent id'));
        $form->switch('is_verified', __('Is verified'));

        return $form;
    }
}
