<?php

namespace App\Listeners;

use App\Events\ReloadEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ReloadEventListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    public function handle(ReloadEvent $event)
    {
        // Handle the custom event
        $message = $event->message;
        // Perform actions based on the event data
        // For example, store the message in session
        session(['custom_message' => $message]);

        // Echo JavaScript to reload the page
        // echo '<script>window.location.reload();</script>';

    }
}
