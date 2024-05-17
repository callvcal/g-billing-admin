<?php

namespace App\Jobs;

use App\Http\Controllers\FirebaseController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WalletCreditJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $token;
    protected $amount;
    protected $total;
    protected $type;
    

    /**
     * Create a new job instance.
     */
    public function __construct($token, $amount,$total,$type="credit")
    {
        $this->token = $token;
        $this->amount = $amount;
        $this->total = $total;
        $this->type = $type;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        if (!isset($this->token)) {
            return;
        }

        if($this->type=='credit'){
            $message="Dear customer Rs. ".$this->amount." is successfully credited into your wallet. Balance is Rs. ".$this->total;
        }else{
            $message="Dear customer Rs. ".$this->amount." is successfully debited from your wallet. Balance is Rs. ".$this->total;
        }

        $data = [
            "title" => "Transaction success",
            "body" => $message,
            "imageUrl" => null,
            // 'order_id' => $order->id,
            'type' => "wallet",
        ];
        $notification = [
            "title" => $data['title'],
            "body" => $data['body'],
            "imageUrl" => $data['imageUrl'],
            "sound" => "default",
        ];

        $controller = new FirebaseController();
        $controller->sendFcmMessage($this->token, $data, $notification);
    
        
    }
}
