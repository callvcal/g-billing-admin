<?php

namespace App\Admin\Actions;

use App\Admin\Controllers\SellController;
use App\Models\Sell;
use App\Models\User;
use Illuminate\Http\Request;
use OpenAdmin\Admin\Actions\RowAction;
use OpenAdmin\Admin\Interactor\Form; // Import the Form class

class OrderAction extends RowAction
{
    public $name = 'Edit Order';

    public $icon = 'icon-edit';
    public function form()
    {
        // disable filling the form with values from the related model
        // $this->addValues(true); // must be placed on top, default = true
        $this->select('order_status', __('Order Status'))->options((new SellController())->orderStatusOptions())->required();
        $this->select('driver_id', __('Assign Delivery boy'))->options(User::where('is_driver', 1)->where('is_verified_driver', 1)->get()->pluck("name", "id"))->required();
        $this->select('delivery_status', __('Delivery Status'))->options((new SellController())->deliveryStatusOptions())->required();
        $this->text('remark', __('Remarks'));
        
    }

    
    public function handle( Request $request)
    {
        // $model->order_status = $request->get('order_status');
        // $model->driver_id = $request->get('driver_id');
        // $model->delivery_status = $request->get('delivery_status');
        // $model->remark = $request->get('remark');
        // $model->save();
        return $this->response()->success('Success message...')->refresh();
    }
}
