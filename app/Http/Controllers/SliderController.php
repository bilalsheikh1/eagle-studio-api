<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return response()->json(Slider::query()->get());
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
            'file' => ['required', 'image'],
        ]);
        try {
            $slider = new Slider();
            $slider->name = $request->file('file')->getClientOriginalName();
            $slider->path = $request->file('file')->storeAs('slider_images', $slider->name, 'public');
            $slider->url = Storage::disk('public')->url($slider->path);
            $slider->user()->associate($request->user()->id);
            $slider->save();
            return response()->json($slider);
        } catch (\Exception $exception){
            return response()->json($exception->getMessage(),500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function show(Slider $slider)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function edit(Slider $slider)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Slider $slider)
    {
        $request->validate([
            'file' => ['required', 'image'],
        ]);
        try {
            $slider->name = $request->file('file')->getClientOriginalName();
            $slider->path = $request->file('file')->storeAs('slider_images', $slider->name, 'public');
            $slider->url = Storage::disk('public')->url($slider->path);
            $slider->user()->associate($request->user()->id);
            $slider->save();
            return response()->json($slider);
        } catch (\Exception $exception){
            return response()->json($exception->getMessage(),500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function destroy(Slider $slider)
    {
        try {
            $res = Storage::disk('public')->delete($slider->path);
            if($res) {
                $slider->delete();
                return response()->json($res);
            }
            return response()->json($res);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }
}
