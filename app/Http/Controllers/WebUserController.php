<?php

namespace App\Http\Controllers;

use App\Models\WebUser;
use Illuminate\Http\Request;

class WebUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pendingUser()
    {
        return response()->json(WebUser::query()->where('active', '2')->get());
    }

    public function activeUser()
    {
        return response()->json(WebUser::query()->where('active','1')->get());
    }

    public function discardUser()
    {
        return response()->json(WebUser::query()->where('active','0')->get());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WebUser  $webUser
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, WebUser $webUser)
    {
        $request->validate([
            'active' => ['required', 'numeric']
        ]);
        try {
            $webUser->active = $request->active;
            $webUser->update();
            return response()->json("user {$webUser->username} status has been updated");
        }catch (\Exception $exception){
            return response()->json($exception->getMessage());
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WebUser  $webUser
     * @return \Illuminate\Http\Response
     */
    public function show(WebUser $webUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WebUser  $webUser
     * @return \Illuminate\Http\Response
     */
    public function edit(WebUser $webUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WebUser  $webUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WebUser $webUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WebUser  $webUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(WebUser $webUser)
    {
        //
    }
}
