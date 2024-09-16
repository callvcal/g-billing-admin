<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\OrderAction;
use App\Admin\Actions\PrepareOrder;
use App\Models\Address;
use App\Models\Menu;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Sell;
use App\Models\SellItem;
use App\Models\User;
use OpenAdmin\Admin\Facades\Admin;
use OpenAdmin\Admin\Widgets\Table;
class PizzaKDSController extends AdminController
{

    
    
    
    
    




    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'KDS';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SellItem());
        // $grid->model()->orderBy('updated_at', "desc"); {

            $grid->model()
            // ->whereDate('created_at', date("Y-m-d")) 
                // Order by id in descending order
                ->where('order_status_prepared', 0)->orderBy('id', 'asc'); // Exclude certain order statuses            
        // }
        // $grid->selector(function (Grid\Tools\Selector $selector) {
        //     $selector->select('order_status_preparing', 'Preparing Status', [0=>"Pending",1=>"Preparing"]);
        //     $selector->select('order_status_prepared', 'Prepared Status', [0=>"Pending",1=>"Prepared"]);
        // });
        // $grid->column('order_status_preparing')->using([
        //     0 => 'Pending Orders',
        //     1 => 'Preparing Orders',
        //     null => 'Pending',
        // ], 'Pending')->dot([
        //     0 => 'primary',
        //     'd_readyToPickup' => 'success',
        //     null => 'danger',
        //     'pending' => 'danger',
        // ], 'danger');
        $grid->disableCreateButton();
        $grid->disableFilter();
        $grid->disableExport();
        $grid->disableActions();
        $grid->disableColumnSelector();
        $grid->disableRowSelector();
        $grid->column('sell.id', __('Order ID'));
        $grid->column('sell.serve_type', __('Order Type'));
        $grid->column('qty', __('Quantity'));
       
        (new RelationController())->gridMenu($grid);

        $action = new PrepareOrder();
        // $grid->column('Start Preparing')->action(PrepareOrder::class)->display(function ($model)  {
        //     return $model;
        // });
        $states = [
            'on' => ['value' => 1, 'text' => 'open', 'color' => 'primary'],
            'off' => ['value' => 0, 'text' => 'close', 'color' => 'default'],
        ];
        $grid->column('order_status_preparing', __('Start Preparing'))->switch($states);
        $grid->column('order_status_prepared', __('Ready To Deliver'))->switch($states);
        $grid->column('sell.cooking_notes', __('Cooking Notes'));
        // (new RelationController())->gridSell($grid);
        
        // $grid->column('sell.order_id', __('ORDER#'));
        
        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableEdit();
            $actions->disableShow();
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
        $show = new Show(SellItem::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('date_time', __('Date time'));
        $show->field('order_id', __('Order id'));
        (new RelationController())->detailsUser($show);
        (new RelationController())->detailsDriver($show);
        (new RelationController())->detailsAddress($show);
        (new RelationController())->detailsSellItems($show);


        $show->field('transaction_id', __('Transaction id'));
        $show->field('payment_method', __('Payment method'));
        $show->field('delivery_pick_up_otp', __('delivery_pick_up_otp'));
        $show->field('order_complete_otp', __('order_complete_otp'));
        $show->field('total_amt', __('Total amt'));
        $show->field('paid_amt', __('Paid amt'));
        $show->field('gst_amt', __('Gst amt'));
        $show->field('gst_type', __('Gst type'));
        $show->field('discount_amt', __('Discount amt'));
        $show->field('due_amt', __('Due amt'));
        $show->field('user_type', __('User type'));
        $show->field('items_count', __('Items count'));
        $show->field('sell_type', __('Sell type'));
        $show->field('serve_type', __('Serve type'));
        $show->field('delivery_tip', __('delivery tip'));
        $show->field('delivery_instruction', __('delivery instruction'));
        $show->field('cooking_notes', __('cooking notes'));
        $show->field('invoice_id', __('Invoice id'));


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
        $form = new Form(new SellItem());
        // $states = [
        //     'on' => ['value' => 1, 'text' => 'open', 'color' => 'primary'],
        //     'off' => ['value' => 0, 'text' => 'close', 'color' => 'default'],
        // ];
        $form->switch('order_status_preparing', __('Start Preparing'));
        $form->switch('order_status_prepared', __('Ready To Deliver'));
        
       
        return $form;
    }
}
