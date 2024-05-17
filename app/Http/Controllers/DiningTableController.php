<?php

namespace App\Http\Controllers;

use App\Models\TableRequest;
use Illuminate\Http\Request;

class DiningTableController extends Controller
{

    public function get($id)  {
        $res = TableRequest::find($id);
        if (!$res) {
            return response([
                'message' => "Table request is deleted by admin"
            ], 401);
        }
        return response($res);
    }

    public function requestTable(Request $request)
    {


        $res = TableRequest::create([
            'user_id' => auth()->user()->id,
            'status' => "pending",
            'guests' => $request->guests,
            'time' => $request->time,
            'date' => $request->date,
            'payment_status'=>'pending',
            'payment_method'=>'n/a',
            'order_id'=> $this->generateOrderID(auth()->user()->id)
        ]);

        return response($res);
    }

    function generateOrderID($user_id)
    {
        $fixedString = "TR-"; // Fixed string
        $capitalString = strtoupper($fixedString); // Convert to uppercase

        $hashedUserId = substr(md5($user_id), 0, 4); // Hash the user ID and take first 4 characters

        $timestamp = time(); // Get current timestamp

        // Concatenate timestamp, fixed string, hashed user ID, and any other desired string
        $orderID = $capitalString . '-' . $timestamp . '-' . $hashedUserId;

        return $orderID;
    }


    public function cancelTable($id)
    {


        $res = TableRequest::find($id);
        if (!$res) {
            return response([
                'message' => "Table request is deleted by admin"
            ], 401);
        }
        $res->status = "cancelled";
        $res->save();

        return response($res);
    }

    function pay(Request $request)
    {
        $id = $request->id;

        $method = $request->payment_method;

        $model = TableRequest::find($id);
        $model->order_id= $model->order_id?? $this->generateOrderID(auth()->user()->id);
        if (!$model) {
            return response([
                'message' => "Table request is deleted by admin"
            ], 401);
        }

        if ($method == "wallet") {
            $model->payment_status = (new WalletTransactionController())->walletPay($model->charge);
            $model->save();
            return response([
                'model' =>
                $model
            ]);
        }

        if ($method == "cash") {
            $model->payment_status = 'pending';
            $model->payment_method = 'cash';
            $model->save();
            return response([
                'model' =>
                $model
            ]);
        }

        $model->payment_status='pending';
        $model->payment_method='razorpay';
        $model->save();

        $response=[
            'model'=>$model
        ];

        $response['razorpay'] = (new RazorPayController())->createTableRequest($model);


        return response($response);

    }
}
