<?php

namespace App\Http\Controllers;

use App\Jobs\LocationWriter;
use App\Models\Location;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use function JmesPath\search;

class LocationController extends Controller
{

    public function index()
    {
        $response = Location::all();
        return response($response);
    }



    ///May cause performance issue
    ///Use JobScheduler (Recommonded)
    function decodeLatLng(Request $request)
    {
        $apiKey = (new AuthController())->apiKey;
        $latitude = $request->latitude;
        $longitude = $request->longitude;

        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$latitude,$longitude&key=$apiKey";

        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->get($url);

            if ($response->getStatusCode() < 250) {
                $body = $response->getBody();
                $map = json_decode($body, true);

                $locations = array();
                $results = $map['results'];

                $selected = new Location();
                $accuracy = 1000;

                if ($results !== null && (count($results) != 0)) {

                    foreach ($results as $result) {
                        $location = new Location();
                        $location->place_id = $result['place_id'];
                        foreach ($result['address_components'] as $place) {
                            $types = $place['types'];
                            if (in_array("locality", $types)) {
                                $location->city = $place['long_name'];
                            } elseif (in_array("postal_code", $types)) {
                                $location->pincode = $place['long_name'];
                            } elseif (in_array("country", $types)) {
                                $location->country = $place['long_name'];
                            } elseif (in_array("administrative_area_level_1", $types)) {
                                $location->state = $place['long_name'];
                            } elseif (in_array("administrative_area_level_2", $types) || in_array("administrative_area_level_3", $types)) {
                                $location->district = $place['long_name'];
                            }
                        }

                        $location->latitude = $result['geometry']['location']['lat'];
                        $location->longitude = $result['geometry']['location']['lng'];
                        $location->address = $result['formatted_address'];





                        // $location->save();
                        array_push($locations, $location);
                    }

                    $job = new LocationWriter($locations);
                    dispatch($job)->afterResponse();
                    return response($locations[0]);
                }
            }
        } catch (Exception $e) {
            Log::channel('callvcal')->info("LocationController error:" . json_encode($e->getTraceAsString()));
            return response([
                'message' => "Sorry for inconvience, there is some issue with this location, please try again"
            ], 401);
        }
        return response([
            'message' => "Sorry for inconvience, there is no known address found at this position"
        ], 401);
    }
    function decodePlaceID($placeID)
    {
        $apiKey = (new AuthController())->apiKey;
        $url = "https://maps.googleapis.com/maps/api/geocode/json?place_id=$placeID&key=$apiKey";

        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->get($url);

            if ($response->getStatusCode() < 250) {
                $body = $response->getBody();
                $map = json_decode($body, true);

                $locations = array();
                $results = $map['results'];

                $selected = new Location();
                $accuracy = 1000;

                if ($results !== null && (count($results) != 0)) {

                    foreach ($results as $result) {
                        $location = new Location();
                        $location->place_id = $result['place_id'];
                        foreach ($result['address_components'] as $place) {
                            $types = $place['types'];
                            if (in_array("locality", $types)) {
                                $location->city = $place['long_name'];
                            } elseif (in_array("postal_code", $types)) {
                                $location->pincode = $place['long_name'];
                            } elseif (in_array("country", $types)) {
                                $location->country = $place['long_name'];
                            } elseif (in_array("administrative_area_level_1", $types)) {
                                $location->state = $place['long_name'];
                            } elseif (in_array("administrative_area_level_2", $types) || in_array("administrative_area_level_3", $types)) {
                                $location->district = $place['long_name'];
                            }
                        }

                        $location->latitude = $result['geometry']['location']['lat'];
                        $location->longitude = $result['geometry']['location']['lng'];
                        $location->address = $result['formatted_address'];

                        // if (isset($location->latitude) && isset($location->longitude)) {
                        //     $distance = $this->calculateDistance($location->latitude, $location->longitude, $latitude, $longitude);
                        //     // if ($distance <= $accuracy) {
                        //     //     $accuracy = $distance;
                        //     //     $selected = $location;
                        //     // }
                        // }



                        // $location->save();
                        array_push($locations, $location);
                    }

                    $job = new LocationWriter($locations);
                    dispatch($job)->afterResponse();
                    return $locations[0];
                }
            }
        } catch (Exception $e) {
            Log::channel('callvcal')->info("LocationController error:" . json_encode($e->getTraceAsString()));
            return null;
        }
        return null;
    }
    function search(Request $request)
    {
        $apiKey = (new AuthController())->apiKey;
        $latitude = $request->latitude ?? 0;
        $longitude = $request->longitude ?? 0;
        $strictbound = $request->strictbound ?? 1;
        $text = $request->text ?? "";
        $sessiontoken = $request->sessiontoken ?? "";
        $rangeInKm = 20 * 1000;
        $countryCode = $request->countryCode ?? "IN";
        $locations = array();
        //       'https://maps.googleapis.com/maps/api/geocode/json?latlng=${position.latitude}%2C${position.longitude}&key=$apiKey';

        if ($strictbound) {
            $url =
                "https://maps.googleapis.com/maps/api/place/autocomplete/json?input=$text&location=$latitude,$longitude&radius=$rangeInKm&strictbounds=$strictbound&components=country:$countryCode&sessiontoken=$sessiontoken&key=$apiKey";
        } else {
            $url =
                "https://maps.googleapis.com/maps/api/place/autocomplete/json?input=$text&location=$latitude,$longitude&components=country:$countryCode&sessiontoken=$sessiontoken&key=$apiKey";
        }


        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->get($url);

            if ($response->getStatusCode() < 250) {
                $body = $response->getBody();
                $map = json_decode($body, true);
                $results = $map['predictions'];

                // if(count($results)==0&&($strictbound==1)){

                //     $request->merge(['strictbound',0]);
                //     return $this->search($request);
                // }

                foreach ($results as $result) {
                    $location = new Location();
                    $location->place_id = $result['place_id'];

                    $location->address = $result['description'];
                    $splits = array_reverse(explode(",", $result['description']));
                    $i = 0;
                    while (count($splits) > 0) {
                        switch ($i) {
                            case 0:
                                $location->country = $splits[0];
                                break;

                            case 1:
                                $location->state = $splits[0];
                                break;
                            case 2:
                                $location->district = $splits[0];
                                break;

                            case 3:
                                $location->city = $splits[0];
                                break;

                            case 4:
                                $location->colony = $splits[0];
                                break;
                            default:
                                $location->colony = $splits[0] . ', ' . $location->colony;
                        }
                        array_shift($splits);
                        $i++;
                    }
                    if (!isset($location->city)) {
                        $location->city = $result['structured_formatting']['main_text'];
                        $splits = array_reverse(explode(",", $location->city));
                        if (count($splits) >= 2) {
                            $location->colony = $splits[0];
                            $location->city = $splits[1];
                        } else {
                            $location->colony = $location->city;
                        }
                    }



                    if (!isset($location->colony)) {
                        $location->colony = $location->city;
                    }

                    $latLngLocation = $this->getLatLng($location->place_id);

                    if (isset($latLngLocation)) {
                        $location->latitude = $latLngLocation->latitude;
                        $location->longitude = $latLngLocation->longitude;
                    }

                    if (isset($location->latitude) && isset($location->longitude)) {
                        $distance = $this->calculateDistance($location->latitude, $location->longitude, $latitude, $longitude);
                        $location->distance = $distance;
                    }


                    // $location->save();

                    array_push($locations, $location);
                }
                usort($locations, function ($a, $b) {
                    return ($a->distance ?? 0) - ($b->distance ?? 0);
                });


                $job = new LocationWriter($locations);
                dispatch($job)->afterResponse();
                return response($locations);
            }
        } catch (Exception $e) {
            Log::channel('callvcal')->info("LocationController error:" . json_encode($e->getTraceAsString()) . json_decode($e));
            return response([
                'message' => "Sorry for inconvience, there is some issue with this location, please try again"
            ], 401);
        }
        return response(array());
    }

    function getLatLng($placeID)
    {
        $location = Location::whereNotNull('latitude')->whereNotNull('longitude')->where('place_id', $placeID)->first();
        if (!$location) {
            $location = $this->decodePlaceID($placeID);
        }
        return $location;
    }

    function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        // Radius of the Earth in kilometers
        $earthRadius = 6371;

        // Convert latitude and longitude from degrees to radians
        $lat1 = deg2rad($lat1);
        $lng1 = deg2rad($lng1);
        $lat2 = deg2rad($lat2);
        $lng2 = deg2rad($lng2);

        // Haversine formula
        $dlat = $lat2 - $lat1;
        $dlng = $lng2 - $lng1;

        $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlng / 2) * sin($dlng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        // Calculate the distance
        $distance = $earthRadius * $c;

        return (int)($distance * 1000); // Distance in kilometers
    }


    public function store(Request $request)
    {
        $id = $request['query'];
        $response1 = Location::where('post', 'like', '%' . $id . '%')->get()->toArray();
        $response2 = Location::where('pincode', 'like', '%' . $id . '%')->get()->toArray();
        $response3 = Location::where('city', 'like', '%' . $id . '%')->get()->toArray();
        $response4 = Location::where('district', 'like', '%' . $id . '%')->get()->toArray();
        $response5 = Location::where('division', 'like', '%' . $id . '%')->get()->toArray();
        $responses = array_unique(array_merge($response1, $response2, $response3, $response4, $response5));
        return response($responses);
    }


    public function update(Request $request, $id)
    {
        $response = Location::where('id', $id)->update($request->all());
        return response($request);
    }
    public function destroy($id)
    {
        $response = Location::findOrfail($id);
        if ($response) {
            $response->delete();
            return response('successs');
        }
        return response(['message' => 'error'], 401);
    }
    public function query(Request $map)
    {
        $qid = $map['qid'];

        switch ($qid) {

            case 1:
                $response = Location::where($map->key1, $map->value1)->get();
                break;
            case 2:
                $response = Location::where([$map->key1 => $map->value1, $map->key2 => $map->value2])->get();
                break;
            default:
                $response = array();
        }

        return response($response);
    }
    public function suggestions(Request $map)
    {
        $latitude = $map->latitude;
        $longitude = $map->longitude;
        $pincode = $map['pincode'];
        $city = $map['city'];
        $district = $map['district'];
        $state = $map['state'];
        $country = $map['country'];

        $res = Location::where('country', 'LIKE', "%$country%")
            ->where('state', 'LIKE', "%$state%")
            ->where('address', 'LIKE', "%$city%")
            ->whereNotNull('colony')->whereNotNull('city')->orderBy("uses")->limit(3)->get();

        return response($res);
    }
}
