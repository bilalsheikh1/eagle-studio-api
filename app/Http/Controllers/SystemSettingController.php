<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SystemSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            return response()->json(SystemSetting::query()->orderBy('position')->get());
        } catch (\Exception $exception){
            return response()->json($exception->getMessage(),500);
        }
    }

    public function getFrontSettings()
    {
        try {
            return response()->json(SystemSetting::query()->where('show_home', 1)->get());
        } catch (\Exception $exception){
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'key' => ['required', 'unique:system_settings,key']
        ]);
        try {
            $settings = new SystemSetting();
            $settings->key = $request->key;
            $settings->value = $request->value;
            $settings->save();
            return response()->json("Setting {$settings->key} has been inserted");
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(),500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SystemSetting  $systemSetting
     * @return \Illuminate\Http\Response
     */
    public function show(SystemSetting $systemSetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SystemSetting  $systemSetting
     * @return \Illuminate\Http\Response
     */
    public function edit(SystemSetting $systemSetting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SystemSetting  $systemSetting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SystemSetting $systemSetting)
    {
//        $request->validate([
//            'key' => ['required', Rule::unique('system_settings')->ignore($systemSetting)]
//        ]);
        try {
            foreach ($request->all() as $value => $index)
                SystemSetting::query()->where("key", "=", $value)->update([
                    "value" => $index
                ]);
            return response()->json("Settings has been updated.");
        } catch (\Exception $exception){
            return response()->json($exception->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SystemSetting  $systemSetting
     * @return \Illuminate\Http\Response
     */
    public function destroy(SystemSetting $systemSetting)
    {
        try {
            $systemSetting->delete();
            return response()->json("Setting {$systemSetting->key} has been deleted");
        } catch (\Exception $exception){
            return response()->json($exception->getMessage(), 500);
        }
    }
}
