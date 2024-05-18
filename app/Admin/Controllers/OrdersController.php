<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\OrderAction;
use App\Admin\Actions\OrderEdit;
use App\Admin\Forms\Setting;
use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Menu;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Sell;
use App\Models\User;
use Carbon\Carbon;
use OpenAdmin\Admin\Facades\Admin;
use OpenAdmin\Admin\Form\Row;
use OpenAdmin\Admin\Layout\Content;
use OpenAdmin\Admin\Widgets\Tab;
use OpenAdmin\Admin\Widgets\Table;

class OrdersController extends Controller
{

    public function orderStatusOptions()
    {
        return [
            '0' => "Accept",
            '1' => "Reject",
            '2' => "cancel",
            '3' => "Complete",
        ];
    }
    public function deliveryStatusOptions()
    {
        return [
            'processing' => "Processing",
            'shipped' => "Shipped",
            'rejected' => "Delivery Boy Rejcted",
            'outForDelivery' => "Out For Delivery",
            'delivered' => "Delivered",
            'returned' => "returned",
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
   
     
    protected function orders(Content $content)
    {
       
        return $content->title("Orders Page")->body($this->grid());

    }


    public function grid()  {
        $grid = new Grid(new Sell());
        $grid->disableCreateButton();
        $grid->disablePagination();
        // $grid->expandFilter();
        $grid->model()->whereDate('created_at',date("Y-m-d"))->orderBy('id',"desc");
       
        
        $grid->actions(function ($actions) {
            $actions->disableDelete();
        });
      
        
        // $grid->filter(function ($filter) {
        //     $filter->disableIdFilter();
        //     $filter->equal('date_time', 'Filter By Order Date')->default(Carbon::now())->date();
        //     $filter->equal('order_status', __('Order status'))->select((new SellController())->orderStatusOptions());
        //     $filter->equal('delivery_status', __('Delivery status'))->select((new SellController())->orderStatusOptions());
        // });

        $grid->column('date_time', __('Date time'));
        $grid->column('order_id', __('Order id'))->expand(function ($model) {

            
            
            $address=Address::find($model->address_id);
            $customer=User::find($model->user_id);
            $driver=User::find($model->driver_id);

            $t2= new Table(['Name', 'content',], [
                ['Customer name:',$customer->name],
                ['Customer Mobile:',$customer->mobile],
                ['Customer Address:',$address->address],
                ['Driver Name',$driver==null?"Not assigned":$driver->name],
                ['Driver Mobile',$driver==null?"Not assigned":$driver->mobile],
                ['Transaction id',$model->transaction_id],
                ['Payment method',$model->payment_method],
                ['Paid amount',$model->paid_amt],
                ['Due amount',$model->due_amt],
                ['Gst type',$model->gst_type],
                ['discount amount',$model->discount_amt],
            ]);

            return $t2;

        });
        // (new RelationController())->gridUser($grid);
        // (new RelationController())->gridDriver($grid);
        
        $grid->column('total_amt', __('Total amt'))->label();

        $grid->column('items_count', __('Items'))->expand(function ($model) {
            $items = $model->items()->get()->map(function ($item) {
                $data= $item->only(['menu_id','qty','total_amt']);
                $data['menu_id']=Menu::find($data['menu_id'])->name;

                return $data;
            });
            return new Table(['Item name', 'quantity','total'], $items->toArray());
            
        })->display(function ($model)  {
            return ($model). "items";
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
        $show = new Show(Sell::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('date_time', __('Date time'));
        $show->field('order_id', __('Order id'));
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
        $show->field('invoice_id', __('Invoice id'));
        $show->field('user_id', __('User id'));
        $show->field('address_id', __('Address id'));
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
        $form->hidden('business_id', __('Business id'))->default(Admin::user()->business_id);
        return $form;
    }
}
