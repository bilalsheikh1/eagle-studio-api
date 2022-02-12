<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        $data=$product->with(['comments' => function($q){
            $q->where('parent_id', 0);
        },'comments.children','comments.children.user','comments.user:id,username'])->get()->pluck('comments');
        if(count($data) > 0)
            return response()->json($data[0]);
        return response()->json([]);
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
    public function store(Product $product, Request $request)
    {
        $request->validate([
            'comment' => ['string', 'required'],
        ]);
        try{
            $comment= new Comment();
            $comment->comment = $request->comment;
            $comment->product()->associate($product->id);
            $comment->user()->associate($request->user()->id);
            $comment->save();
            $data=$product->with(['comments' => function($q){
                $q->where('parent_id', 0);
            },'comments.children','comments.children.user','comments.user:id,username'])->get()->pluck('comments');
            if(count($data) > 0)
                return response()->json($data[0]);
            return response()->json([]);
        }catch (\Exception $exception){
            return response()->json($exception->getMessage(), 500);
        }
    }

    public function commentReply(Product $product, Request $request)
    {
//        $request->validate([
//            'id' => ['int', 'required', 'comments:exist,id'],
//            'comment' => ['string', 'required' ],
//        ]);
        try{
            $comment = new Comment();
            $comment->comment = $request->comment;
            $comment->parent()->associate($request->id);
            $comment->user()->associate(1);
            $product->comments()->save($comment);
            $data=$product->with(['comments' => function($q){
                $q->where('parent_id', 0);
            },'comments.children','comments.children.user','comments.user:id,username'])->get()->pluck('comments');
            if(count($data) > 0)
                return response()->json($data[0]);
            return response()->json([]);
        } catch (\Exception $exception){
            return response()->json($exception->getMessage(), 500);
        }
    }

    public function fetchCommandsByUser(Product $product)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        //
    }
}
