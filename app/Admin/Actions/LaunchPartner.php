<?php

namespace App\Admin\Actions;

use App\Models\AdminUser;
use Illuminate\Database\Eloquent\Model;
use OpenAdmin\Admin\Actions\RowAction;
use OpenAdmin\Admin\Facades\Admin;

class LaunchPartner extends RowAction
{
    public $name = 'dashboard';

    public $icon = 'icon-home';

    public function handle(Model $model)
    {
        // $model ...
        $user=AdminUser::find(Admin::user()->id);
        $user->business_id=$model->id;
        $user->business_key=$model->name;
        $user->save();

        return $this->response()->success('Success message.')->refresh();
    }
    // public function href()
    // {
        

    //     return "/admin";
    // }
}