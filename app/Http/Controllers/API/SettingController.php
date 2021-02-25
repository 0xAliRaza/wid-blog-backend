<?php

namespace App\Http\Controllers\API;

use App\settings;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('viewAny', Setting::class);
        $request->validate([
            'title' => 'required|string|max:60',
            'meta_title' => 'required|string|max:60',
            'meta_description' => 'nullable|string|max:160'
        ]);


        $setting = Setting::firstOrNew();

        $setting->title = $request->title;
        $setting->meta_title = $request->meta_title;
        $setting->meta_description = $request->meta_description;
        if ($setting->saveOrFail()) {
            return response()->json($setting);
        }
        return $this->unknownErrorResponse();
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $this->authorize('viewAny', Setting::class);
        $setting = Setting::first();
        if ($setting && $setting->exists) {
            return response()->json($setting);
        }
        return response()->json([]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $this->authorize('viewAny', Setting::class);
        $setting = Setting::first();
        if ($setting && $setting->exists) {
            return response()->json($setting->delete());
        }
        return response()->json(['message' => 'Setting not found.'], 404);
    }
}
