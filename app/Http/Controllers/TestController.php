<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test(){
        $comment = Comment::query()->with(['children','children.user', 'user:id,username'])->get();
//        dd($comment);
        return response()->json($comment);
    }
}
