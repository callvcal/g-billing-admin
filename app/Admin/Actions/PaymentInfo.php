<?php

namespace App\Admin\Actions;

use Illuminate\Database\Eloquent\Model;
use OpenAdmin\Admin\Actions\RowAction;

class PaymentInfo extends RowAction
{
    public $name = 'info';

    public $icon = 'icon-info';

    public function handle(Model $model)
    {
        // $model ...

        return $this->response()->success('Success message.')->refresh();
    }
    public function dialog()
    {
        $this->question('Are you sure to copy this row?', 'This will copy all the data into a new entity', ['icon'=>'question','confirmButtonText'=>'Yes']);
    }

}