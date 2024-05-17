<?php

namespace App\Http\Controllers;

use App\Admin\Forms\Setting as FormsSetting;
use App\Models\Setting;
use Illuminate\Http\Request;
use OpenAdmin\Admin\Layout\Content;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Content $content)
    {
        return $content->title("Settings Page")->body(new FormsSetting());
    }

    public function update(Request $request)
    {
        $settings = Setting::updateOrCreate(
            [
                'id' => 1,
            ],

            [
                'json' => $request->all()
            ]

        );
        return response($settings);
    }
}
