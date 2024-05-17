<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReferalRequest;
use App\Http\Requests\UpdateReferalRequest;
use App\Models\Referal;
use App\Models\ReferalTransaction;
use App\Models\User;

class ReferalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function referedByMe()
    {
        return ReferalTransaction::where('referring_user_id', auth()->user()->id)->latest()->get();
    }

    public function getRefer()
    {
        $refer = Referal::where('user_id', auth()->user()->id)->first();
        if ($refer == false) {
            $refer = Referal::create(
                [
                    'user_id' => auth()->user()->id,

                    'active' => 1,
                    'mobile' => auth()->user()->mobile,
                    'name' => auth()->user()->name,
                    'referance_code' => null,
                    'refered_count' => 0,
                ]
            );
        }
        return $refer;
    }

    public function getReferWithID($id)
    {
        $user=User::find($id);
        $refer = Referal::where('user_id',$id)->first();
        if ($refer == false) {
            $refer = Referal::create(
                [
                    'user_id' => $user->id,

                    'active' => 1,
                    'mobile' => $user->mobile,
                    'name' => $user->name,
                    'referance_code' => null,
                    'refered_count' => 0,
                ]
            );
        }
        return $refer;
    }


    public function summary($code)
    {

        $rewardPoints = (new RewardController())->getMyPoints();
        $myreferals = $this->referedByMe();
        $refer = $this->getRefer();
        if(isset($code)&&!isset($refer->referance_code)){
            $refer->referance_code=$code;
            $refer->save();
        }

        return response([
            'rewardPoints' => $rewardPoints,
            'myReferals' => $myreferals,
            'refer' => $refer
        ]);
    }
}
