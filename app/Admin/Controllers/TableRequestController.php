<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\RazorPayController;
use App\Models\DiningTable;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\TableRequest;
use OpenAdmin\Admin\Facades\Admin;

class TableRequestController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'TableRequest';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new TableRequest());
        //(new RelationController())->gridActions($grid);
        $grid->model()->orderBy('updated_at',"desc");
        (new RelationController())->gridTable($grid);
        (new RelationController())->gridUser($grid);

        $grid->column('id', __('Id'))->sortable();
        $grid->column('guests', __('Guests'))->sortable();
        $grid->column('date', __('Date'))->sortable();
        $grid->column('time', __('Time'))->sortable();
        $grid->column('charge', __('Charge'))->sortable();
        $grid->column('status', __('Status'))->sortable();
        $grid->column('payment status', __('Payment Status'))->sortable();
        $grid->column('payment method', __('Payment Method'))->sortable();
        

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
        $show = new Show(TableRequest::findOrFail($id));
        (new RelationController())->detailsUser($show);
        (new RelationController())->detailsTable($show);
        $show->field('id', __('Id'));
        $show->field('order_id', __('Order ID'));
        $show->field('guests', __('Guests'));
        $show->field('date', __('Date'));
        $show->field('time', __('Time'));
        $show->field('charge', __('Charge'));
        $show->field('status', __('Status'));
        $show->field('payment_method', __('payment method'));
        $show->field('payment status', __('payment status'));
        $show->field('transaction_id', __('Razorpay Details'))->as(function ($id) {
            return (new RazorPayController())->showAt($id);
        })->json();

        $show->field('order_id', __('order ID'));
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
        $form = new Form(new TableRequest());

        // $form->number('user_id', __('User id'));
        $form->number('guests', __('Guests'));
        $form->date('date', __('Date'))->default(date('Y-m-d'));
        $form->time('time', __('Time'))->default(date('H:i:s'));
        $form->number('charge', __('Charge'))->required();
        $form->select('status', __('Booking Status'))->options([
            'accepted' => "Accept",
            'rejected' => "Reject",
            'completed' => "Complete",
        ])->required();
        $form->select('payment_method', __('Payment method'))->options([
            'cash' => "Cash",
            'razorpay' => "Razorpay",
            'wallet' => "Wallet",
        ]);

        $form->select('payment_status', __('Payment status'))->options([
            'pending' => "Pending",
            'paid' => "Paid",
            'transaction_failed' => "Transaction failed",
        ]);
        $form->select('dining_table_id', __('Set dining table'))->options((new HomeController())->query(DiningTable::class)->get()->pluck("name","id"))->required();
        $form->hidden('admin_id', __('Admin id'))->default(Admin::user()->id);
        
        return $form;
    }
}
