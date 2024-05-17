<?php

namespace App\Jobs;

use App\Models\Location;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class LocationWriter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $locations;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($locations)
    {
        $this->locations = $locations;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->locations as $location) {

            // $exists = Location::where('place_id', $location->place_id)->first();
            // if ($exists) {
            //     $location->id = $exists->id;
            // }
            // $location->save();

            if (
                isset($location->city) &&
                isset($location->pincode) &&
                isset($location->address) &&
                isset($location->latitude) &&
                isset($location->longitude) &&
                isset($location->country) &&
                isset($location->state)

            ) {
                Location::updateOrInsert(
                    ['place_id' => $location->place_id], // Unique constraint field(s)
                    [
                        'country' => $location->country,
                        'state' => $location->state,
                        'district' => $location->district,
                        'city' => $location->city,
                        'pincode' => $location->pincode,
                        'colony' => $location->colony,
                        'address' => $location->address,
                        'latitude' => $location->latitude,
                        'longitude' => $location->longitude,
                        'uses' => DB::raw('uses + 1'), // Increment 'uses' field by 1

                        // Add other fields as needed
                    ]
                );
            }
        }
    }
}
