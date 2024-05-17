<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Address;

class AddressController extends AdminController
{

    public function controller()
    {
        return   new RelationController();
    }

    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Address';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Address());
        $grid->model()->orderBy('updated_at', "desc");
        $grid->disableCreateButton();




        $grid->column('id', __('Id'))->sortable();
        $this->controller()->gridUser($grid);
        (new RelationController())->gridActions($grid);
        $grid->column('name', __('Name'))->sortable();
        $grid->column('pincode', __('Pincode'))->sortable();
        $grid->column('city', __('City'))->sortable();
        $grid->column('district', __('District'))->sortable();
        $grid->column('state', __('State'))->sortable();
        $grid->column('country', __('Country'))->sortable();
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
        $show = new Show(Address::findOrFail($id));

        $show->field('id', __('Id'));
        $this->controller()->detailsUser($show);
        $show->field('name', __('Name'));
        $show->field('plot_no', __('Plot no'));
        $show->field('pincode', __('Pincode'));
        $show->field('city', __('City'));
        $show->field('district', __('District'));
        $show->field('state', __('State'));
        $show->field('country', __('Country'));
        $show->field('address', __('Address'));
        $show->field('latitude', __('Latitude'));
        $show->field('longitude', __('Longitude'));
        $show->field('user_id', __('User id'));
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
        $form = new Form(new Address());

        $form->text('name', __('Name'));
        $form->text('plot_no', __('Plot no'));
        $form->number('pincode', __('Pincode'));
        $form->text('city', __('City'));
        $form->text('district', __('District'));
        $form->text('state', __('State'));
        $form->text('country', __('Country'));
        $form->textarea('address', __('Address'));
        $form->decimal('latitude', __('Latitude'));
        $form->decimal('longitude', __('Longitude'));
        $form->number('user_id', __('User id'));

        return $form;
    }
}
