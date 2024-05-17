<?php

namespace App\Jobs;

use App\Http\Controllers\FirebaseController;
use App\Models\PushNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PushNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;

    /**
     * Create a new job instance.
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $model = PushNotification::find($this->id);
        if (!$model) {
            return;
        }

        $controller = new FirebaseController();


        $controller->sendFcmToTopic('offers', null, [
            'title'=>$model->title,
            'body'=>$model->body,
            
            "image" => env('disk_path').'/'.$model->image,

        ]);
    }
}
