<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
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

    public function getProfile(Request $request)
    {
//        return response()->json($request->user()->wi);
    }

    public function getUsers(Request $request)
    {
        return response()->json(User::query()->where('is_admin', '!=' , '1')->where('id', '!=',$request->user()->id)->paginate($request->pageSize));
    }

    public function getFilteredUsers(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $users = User::query();
            if(isset($request->filters['username']))
                $users->where('username', $request->filters['username']);
            if(isset($request->filters['name']))
                $users->where('name', $request->filters['name']);
            if(isset($request->filters['email']))
                $users->where('email', $request->filters['email']);
            return response()->json($users->paginate($request->pagination['pageSize']));
        } catch (\Exception $exception){
            return response()->json($exception->getMessage(), 500);
        }
    }

    public function updateStatus(User $user, $status): \Illuminate\Http\JsonResponse
    {
        try {
            $user->active = $status;
            $user->save();
            return response()->json("user {$user->username} status updated");
        }catch (\Exception $exception) {
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
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        try {
            return response()->json($user->load(['products' => function($q){
                $q->where('status', 1);
            }]));
        } catch (\Exception $exception){
            return response()->json($exception->getMessage(), 500);
        }
    }

    public function getUser($id)
    {
        try{
            $user = User::query()->where('id', Crypt::decrypt($id))->first();
            return response()->json($user);
        } catch (\Exception $exception){
            return response()->json($exception->getMessage(), 500);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            "usrname"=> ['required','email'],
            'password'=> ['required', '']
        ]);
        try {
            if(Auth::attempt())
            {
                return response()->json($request->user());
            }
        } catch (\Exception $exception){
            return response()->json($exception->getMessage(), 500);
        }
    }

    public function changePassword(User $user, Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed']
        ]);
        try {
            if (!Hash::check($request->current_password, $user->password))
                return response()->json('Current password does`nt match');
            $user->password = bcrypt($request->password);
            $user->save();
            return response()->json("Password changed");
        } catch (\Exception $exception){
            return response()->json($exception->getMessage(), 500);
        }
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
