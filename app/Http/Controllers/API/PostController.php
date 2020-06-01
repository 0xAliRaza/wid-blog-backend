<?php

namespace App\Http\Controllers\API;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\PostsTrait;
use Symfony\Component\Console\Input\Input;

class PostController extends Controller
{

    use PostsTrait;

    /**
     * Create a new PostController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = $this->getAll();
        return response()->json(['posts' => $posts->toArray()]);
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
            'title' => 'required|unique:posts|min:3|max:255',
            'content' => 'required|min:3|max:20000',
            'type_id' => 'required|integer|exists:App\Models\Type,id|max:255',
            'user_id' => 'required|integer|exists:App\Models\User,id|max:255'
        ]);
        $post = $this->manipulate($this->get(), $request, ['title', 'content', 'type_id', 'use_id']);
        return response()->json($post->save());
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:posts|max:255',
            'title' => 'required|unique:posts,title,' . (int) $request->id . '|min:3|max:255',
            'content' => 'required|min:3|max:20000',
            'type_id' => 'required|integer|exists:App\Models\Type,id|max:255',
            'user_id' => 'required|integer|exists:App\Models\User,id|max:255'
        ]);
        $post = $this->get((int) $request->id);

        if ($post->isEmpty()) {
            return response()->json(["message" => "Post not found in the database."], 404);
        }

        $this->manipulate($post, $request, ['title', 'content', 'type_id', 'user_id']);
        return response()->json($post->save());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:posts|max:255',
        ]);
        $post = $this->get((int) $request->id);

        if ($post->isEmpty()) {
            return response()->json(["message" => "Post not found in the database."], 404);
        }

        return response()->json($post->delete());
    }
}
