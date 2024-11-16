<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\RazorPayController;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\PaymentTransaction;

class PaymentTransactionController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'PaymentTransaction';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PaymentTransaction());
        $grid->quickSearch('location_id');

        $grid->model()->orderBy('id',"DESC");
        $grid->column('id', __('Id'))->sortable();
        $grid->column('order_id', __('Order id'))->sortable();
        $grid->column('transaction_id', __('Transaction id'));
        $grid->user()->display(function ($model) {
            if ($model == null) {
                return '<span class="badge rounded-pill bg-success">' . "N/A" . '</span> ';
            }
            return '<span class="badge rounded-pill bg-success">' . $model['name'] . '</span> ';
        })->sortable();
        $grid->column('location_id', __('Location id'));
        $grid->business()->display(function ($model) {
            if ($model == null) {
                return '<span class="badge rounded-pill bg-success">' . "N/A" . '</span> ';
            }
            return '<span class="badge rounded-pill bg-success">' . $model['name'] . '</span> ';
        })->sortable();
        $grid->column('location_id', __('Location id'))->sortable();
        $grid->column('date_time', __('Date time'))->sortable();
        $grid->column('transaction_status_local', __('Transaction status local'))->sortable();
        $grid->column('transaction_status_callback', __('Transaction status callback'))->sortable();
        $grid->column('plan', __('Plan'))->sortable();
        $grid->column('amount', __('Amount'))->sortable();
        $grid->column('gst', __('Gst'));
        $grid->column('service_charge', __('Service charge'))->sortable();
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
        $show = new Show(PaymentTransaction::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('order_id', __('Order id'));
        $show->field('transaction_id', __('Transaction id'));
        $show->user('User information', function ($model) {

            $model->setResource('/admin/users');

            $model->id();
            $model->name();
            $model->email();
            $model->mobile();
            $model->location();
        });
        $show->field('json', __('Json'))->json();
        $show->business('business information', function ($model) {
            $model->setResource('/admin/users');
            $model->id();
            $model->name();
            $model->plan();
        });
        $show->field('transaction_id', __('Razorpay Details'))->as(function ($id) {
            return (new RazorPayController())->showAt($id);
        })->json();
        $show->field('date_time', __('Date time'));
        $show->field('transaction_status_local', __('Transaction status local'));
        $show->field('transaction_status_callback', __('Transaction status callback'));
        $show->field('callback_json', __('Callback json'))->json();
        $show->field('plan', __('Plan'));
        $show->field('amount', __('Amount'));
        $show->field('gst', __('Gst'));
        $show->field('service_charge', __('Service charge'));
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
        $form = new Form(new PaymentTransaction());

        $form->text('order_id', __('Order id'));
        $form->text('transaction_id', __('Transaction id'));
        $form->number('user_id', __('User id'));
        $form->text('json', __('Json'));
        $form->number('location_id', __('Location id'));
        $form->datetime('date_time', __('Date time'))->default(date('Y-m-d H:i:s'));
        $form->text('transaction_status_local', __('Transaction status local'));
        $form->text('transaction_status_callback', __('Transaction status callback'));
        $form->text('callback_json', __('Callback json'));
        $form->text('plan', __('Plan'));
        $form->decimal('amount', __('Amount'));
        $form->decimal('gst', __('Gst'));
        $form->decimal('service_charge', __('Service charge'));

        return $form;
    }
}
