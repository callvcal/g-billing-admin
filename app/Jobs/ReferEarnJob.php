<?php

namespace App\Jobs;

use App\Http\Controllers\FirebaseController;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReferEarnJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $referred_user_id;
    protected $referred_user_reward;
    protected $referring_user_id;
    protected $referring_user_reward;
    protected $type;


    /**
     * Create a new job instance.
     */
    public function __construct($referring_user_id, $referred_user_id, $referring_user_reward, $referred_user_reward, $type = 'normal')
    {
        $this->referred_user_id = $referred_user_id;
        $this->referring_user_id = $referring_user_id;
        $this->referring_user_reward = $referring_user_reward;
        $this->referred_user_reward = $referred_user_reward;
        $this->type = $type;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $referred_user = User::find($this->referred_user_id);
        $message = '';
        if ($this->type == 'special') {
            $message = "Congratulation, we have successfully alloted special discount for first 3 orders.";
        }

        if ($this->type == 'normal') {
            $message = "Congratulation you received " . $this->referred_user_reward . " rewards";
            $referring_user = User::find($this->referring_user_id);

            if (!$referred_user || !$referring_user) {
                return;
            }

            if (isset($referring_user->fcm_token)) {
                $data = [
                    "title" => "Your Friend " . $referred_user->name . " installed app",
                    "body" => "Congratulation you received " . $this->referring_user_reward . " rewards",
                    "imageUrl" => null,
                    // 'order_id' => $order->id,
                    'type' => "refer",
                ];
                $notification = [
                    "title" => $data['title'],
                    "body" => $data['body'],
                    "imageUrl" => $data['imageUrl'],
                    "sound" => "default",
                ];

                $controller = new FirebaseController();
                $controller->sendFcmMessage($referring_user->fcm_token, $data, $notification);
            }
        }


        if (isset($referred_user->fcm_token)) {


            $data = [
                "title" => "Welcome bonus credited",
                "body" => $message,
                "imageUrl" => null,
                // 'order_id' => $order->id,
                'type' => "refer",
            ];
            $notification = [
                "title" => $data['title'],
                "body" => $data['body'],
                "imageUrl" => $data['imageUrl'],
                "sound" => "default",
            ];

            $controller = new FirebaseController();
            $controller->sendFcmMessage($referred_user->fcm_token, $data, $notification);
        }
    }
}
