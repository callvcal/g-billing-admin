<?php

namespace App\Admin\Actions;

use App\Models\PageDesigner;
use App\Models\SellItem;
use Illuminate\Database\Eloquent\Model;
use OpenAdmin\Admin\Actions\RowAction;

class PrepareOrder extends RowAction
{


    // public function handle(Model $model)
    // {

    //     return $this->response()->success('Success message.')->refresh();
    // }
    public function handle(SellItem $page)
    {
        $page->order_status = 'c_preparing';
        $page->save();

        $html = $page->order_status=='c_preparing' ? "<i class=\"icon-star\"></i>" : "Start Preparing" ;

        return $this->response()->html($html);
    }

    // public function display($order_status)
    // {
    //     return $order_status=='c_preparing' ? "<i class=\"icon-star\"></i>" :$order_status. "Start Preparing";
    // }

}