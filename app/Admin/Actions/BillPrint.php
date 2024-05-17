<?php

namespace App\Admin\Actions;

use Illuminate\Database\Eloquent\Model;
use OpenAdmin\Admin\Actions\RowAction;

class BillPrint extends RowAction
{
    public $name = 'order-edit';

    public $icon = 'icon-order-edit';

    // public function href()
    // {
    //     return "/admin/sells";
    // }
    public function handle(Model $model)
    {
        // $model ...
        return "/admin/sells";
    // return $this->response()->success('Success message.')->refresh();
    }
    
}
