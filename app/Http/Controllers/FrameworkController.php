<?php

namespace App\Http\Controllers;

use App\Models\Framework;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class FrameworkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Framework::query()->get());
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
            "name" => ["required", "string"]
        ]);
        try {
            $framework = new Framework();
            $framework->name = $request->name;
            $framework->save();
            return response()->json("Framework ". $framework->name ." has been inserted");
        }catch (\Exception $exception){
            return response()->json($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Framework  $framework
     * @return \Illuminate\Http\Response
     */
    public function show(Framework $framework)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Framework  $framework
     * @return \Illuminate\Http\Response
     */
    public function edit(Framework $framework)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Framework  $framework
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Framework $framework)
    {
        $request->validate([
            "name" => ["required", "string"]
        ]);
        try {
            $framework->name = $request->name;
            $framework->save();
            return response()->json("Framework ". $framework->name ." has been updated");
        }catch (\Exception $exception){
            return response()->json($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Framework  $framework
     * @return \Illuminate\Http\Response
     */
    public function destroy(Framework $framework)
    {
        try {
            $framework->delete();
            return response()->json("framework ".$framework->name ." has been deleted");
        }catch (\Exception $exception){
            return response()->json($exception->getMessage());
        }
    }
}
