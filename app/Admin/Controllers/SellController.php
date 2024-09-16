<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\OrderAction;
use App\Http\Controllers\RazorPayController;
use App\Models\Address;
use App\Models\Business;
use App\Models\Menu;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Sell;
use App\Models\User;
use OpenAdmin\Admin\Controllers\Dashboard;
use OpenAdmin\Admin\Facades\Admin;
use OpenAdmin\Admin\Layout\Column;
use OpenAdmin\Admin\Layout\Row;
use OpenAdmin\Admin\Widgets\Collapse;
use OpenAdmin\Admin\Widgets\Table;

class SellController extends AdminController
{

    public function orderStatusOptions()
    {
        return [
            'a_sent' => "Received",
            'b_accepted' => "Accept",
            'f_rejected' => "Reject",
            'c_preparing' => "Start Preparing",
            'd_readyToPickup' => "Ready to Pickup",
            'g_cancelled' => "cancel",
            'e_completed' => "Complete",
        ];
    }
    public function orderStatusOptionsSelector()
    {
        return [
            'a_sent' => "Received",
            'b_accepted' => "Accepted",
            'f_rejected' => "Rejected",
            'c_preparing' => "Preparing",
            'd_readyToPickup' => "Ready",
            'g_cancelled' => "cancelled",
            'e_completed' => "Completed",
        ];
    }
    public function orderItemStatusOptions()
    {
        return [
            'c_preparing' => "Start Preparing",
            'd_readyToPickup' => "Ready to Deliver",
        ];
    }
    public function deliveryStatusOptions()
    {
        return [
            'a_unassigned' => "Not Assigned",
            'b_assigned' => "Assigned",
            'c_accepted' => "Delivery Partner Accepted",
            'h_rejected' => "Delivery Partner Rejected",
            'd_pickedUp' => "Delivery Partner Picked Up",
            'e_outForDelivery' => "Out For Delivery",
            'f_delivered' => "Delivered",
            'g_returned' => "returned",
        ];
    }
    public function deliveryStatusOptionsSelector()
    {
        return [
            'a_unassigned' => "Not Assigned",
            'b_assigned' => "Assigned",
            'c_accepted' => "Delivery Accepted",
            'h_rejected' => "Delivery Rejected",
            'd_pickedUp' => "Picked Up",
            'e_outForDelivery' => "Out For Delivery",
            'f_delivered' => "Delivered",
            'g_returned' => "returned",
        ];
    }




    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Sell';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Sell());
        $grid->model()->orderBy('updated_at', "desc");
        $grid->selector(function (Grid\Tools\Selector $selector) {
            $selector->select('order_status', 'Order Status', $this->orderStatusOptionsSelector());
            $selector->select('delivery_status', 'Delivery Status', $this->deliveryStatusOptionsSelector());
        });
        if (!canAllowEdit()) {
            $grid->disableCreateButton();
            $grid->disableFilter();
            $grid->disablePagination();
            $grid->disableExport();
            $grid->disableActions();
            $grid->disableColumnSelector();
            $grid->disableRowSelector();
            $grid->model()->whereDate('created_at', date("Y-m-d")) // Filter by today's date
                ->whereNotIn('order_status', ['e_completed', 'g_cancelled', 'f_rejected'])->orderBy('id', 'desc'); // Exclude certain order statuses            

            // $grid->fixHeader();
            // $grid->model()->whereDate('created_at', date("Y-m-d")) // Filter by today's date
            //     ->whereNotIn('order_status', ['e_completed', 'g_cancelled', 'f_rejected'])->orderBy('id', 'desc'); // Exclude certain order statuses            
        }
        $grid->expandFilter();

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('created_at')->date();
            
            
        });

        $grid->column('serve_type', __('Order Type'))->sortable()->label();
        $grid->column('print_bill', 'Bill')->display(function ($modelNull) {
            $id = $this->getKey();
            return "<button onclick='printBill($id)' class='btn btn-primary'>Bill Print</button>";
        });
        $grid->column('kot_bill', 'KOT')->display(function ($modelNull) {
            $id = $this->getKey();
            return "<button onclick='printKOT($id)' class='btn btn-warning'>KOT Print</button>";
        });
        $grid->column('stickers', 'Stickers')->display(function ($modelNull) {
            $id = $this->getKey();
            return "<button onclick='printSticker($id)' class='btn btn-warning'>Sticker Print</button>";
        });
        $grid->column('id', __('#id'))->expand(function ($model) {

            $discount = (($model->special_discount_id != null) ? 'special discount, ' : '') . (($model->special_discount_id != null) ? 'coupon' : '');

            $address = Address::find($model->address_id);
            $customer = User::find($model->user_id);
            $driver = User::find($model->driver_id);

            $t1 = new Table(['Content', 'Content',], [
                ['Order ID:' . $model->order_id, 'Transaction id: ' . $model->transaction_id],
                ['Customer Mobile: ' . (($customer == null) ? "Not assigned" : $customer->mobile), 'Driver Mobile: ' . (($driver == null) ? "Not assigned" : $driver->mobile)],
                ['Payment method: ' . $model->payment_method, 'Payment Status ' . $model->payment_status],
                ['Paid amount: ' . "Rs. " . $model->paid_amt, 'Due amount: ' . 'Rs. ' . $model->due_amt],
                ['discount amount: ' . 'Rs. ' . $model->discount_amt, $discount],
                ['Customer Address:', ($address != null) ? $address->address : ''],
            ]);
            $items = $model->items()->get()->map(function ($item) {
                $data = $item->only(['menu_id', 'qty', 'total_amt']);

                $menu = Menu::find($data['menu_id']);
                if ($menu) {
                    $data['menu_id'] = $menu->name;
                }

                return $data;
            });

            $t2 = (new Table(['Item name', 'quantity', 'total'], $items->toArray()));

            $collapse = new Collapse();

            $collapse->add('Details', $t1->render());
            $collapse->add('Items', $t2->render());

            return $collapse->render();
        })->sortable();
        $grid->column('date_time', __('Date time'))->sortable();

        $grid->column('order_status', __('Order Status'))->select((new SellController())->orderStatusOptions());
        $grid->column('driver_id', __('Assign Delivery boy'))->select(User::where('is_driver', 1)->where('is_verified_driver', 1)->get()->pluck("name", "id")->toArray());


        $grid->column('payment_method', __('Payment method'))->sortable();
        $grid->column('payment_status', __('Payment status'))->sortable();


        $grid->column('total_amt', __('Total amt'))->sortable()->label();
        $grid->column('paid_amt', __('Paid amt'))->sortable();
        $grid->column('due_amt', __('Due amt'))->sortable();
        $grid->column('delivery_status', __('Delivery Status'))->select((new SellController())->deliveryStatusOptions());




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
        $show = new Show(Sell::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('date_time', __('Date time'));
        $show->field('order_id', __('Order id'));
        (new RelationController())->detailsUser($show);
        (new RelationController())->detailsDriver($show);
        (new RelationController())->detailsAddress($show);
        (new RelationController())->detailsSellItems($show);
        //(new RelationController())->showTools($show);


        $show->field('transaction_id', __('Razorpay Details'))->as(function ($id) {
            return (new RazorPayController())->showAt($id);
        })->json();


        $show->field('payment_method', __('Payment method'));
        $show->field('delivery_pick_up_otp', __('delivery_pick_up_otp'));
        $show->field('order_complete_otp', __('order_complete_otp'));
        $show->field('total_amt', __('Total amt'));
        $show->field('paid_amt', __('Paid amt'));
        $show->field('gst_amt', __('Gst amt'));
        $show->field('order_status', __('order status'));
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
        $form = new Form(new Sell());

        $form->datetime('date_time', __('Date time'))->default(date('Y-m-d H:i:s'));
        $form->text('order_id', __('Order id'));
        $form->text('transaction_id', __('Transaction id'));
        $form->text('payment_method', __('Payment method'));
        $form->number('total_amt', __('Total amt'));
        $form->number('paid_amt', __('Paid amt'));
        $form->text('gst_amt', __('Gst amt'));
        $form->text('gst_type', __('Gst type'));
        $form->number('discount_amt', __('Discount amt'));
        $form->number('due_amt', __('Due amt'));
        $form->text('user_type', __('User type'));
        $form->number('items_count', __('Items count'));
        $form->text('sell_type', __('Sell type'));
        $form->select('order_status', __('Order Status'))->options($this->orderStatusOptions());
        $form->select('delivery_status', __('Delivery Status'))->options($this->deliveryStatusOptions());
        $form->select('driver_id', __('Assign Delivery boy'))->options(User::where('is_driver', 1)->where('is_verified_driver', 1)->get()->pluck("name", "id"));
        $form->number('invoice_id', __('Invoice id'));
        $form->number('user_id', __('User id'));
        $form->number('address_id', __('Address id'));
        $form->hidden('admin_id', __('Admin id'))->default(Admin::user()->id);
        
        return $form;
    }
}
