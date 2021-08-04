<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response()->json(User::query()->where('id', '!=', auth()->user()->id)->where('active', $request->active)->get());
    }

    public function getActiveUser()
    {
        return response()->json(User::query()->where('id', '!=', auth()->user()->id)->where('active', 1)->get());
    }

    public function getDeactiveUser()
    {
        return response()->json(User::query()->where('id', '!=', auth()->user()->id)->where('active', 0)->get());
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
            'name' => ['required', 'string', 'min:3', 'max:200'],
            'username' => ['required', 'string', 'unique:users', 'min:3', 'max:250'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:3', 'confirmed']
        ]);
        try{
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->username = $request->username;
            $user->password = Hash::make($request->password);
            $user->save();
            return response()->json("user {$user->username} has been inserted");
        }catch (\Exception $exception){
            return response()->json($exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:200'],
            'username' => ['required', 'string', 'unique:users', 'min:3', 'max:250'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:3', 'confirmed']
        ]);
        try{
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->username = $request->username;
//            $user->password = Hash::make($request->password);
            $user->update();
            return response()->json("user {$user->username} has been updated");
        }catch (\Exception $exception){
            return response()->json($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        try {
            $user->delete();
            return response()->json("user {$user->username} has been deleted");
        }catch (\Exception $exception){
            return response()->json($exception->getMessage());
        }
    }
}
