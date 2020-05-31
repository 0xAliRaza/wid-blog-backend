<?php

namespace App\Http\Controllers\API;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\Console\Input\Input;

class PostController extends Controller
{


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
        $posts = Post::all();
        foreach ($posts as $post) {
            $post->user = $post->user;
            $post->type = $post->type;
        }
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
        if (empty(auth('api')->user()->id) || (int) $request->user_id !== auth('api')->user()->id) {
            return response()->json(['message' => 'The given post user details did not match with authorized user.'], 400);
        }
        $post = new Post();
        $post->title = $request->title;
        $post->content = $request->content;
        $post->type_id = $request->type_id;
        $post->user_id = $request->user_id;
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
            'id' => 'required|exists:App\Models\Post|max:255',
            'title' => 'required|unique:posts,title,' . (int) $request->id . '|min:3|max:255',
            'content' => 'required|min:3|max:20000',
            'type_id' => 'required|integer|exists:App\Models\Type,id|max:255',
            'user_id' => 'required|integer|exists:App\Models\User,id|max:255'
        ]);
        $post = Post::find((int) $request->id);
        if (empty(auth('api')->user()->id) || (int) $request->user_id !== auth('api')->user()->id || $post->user->id !== auth('api')->user()->id) {
            return response()->json(['message' => 'The given post user details did not match with authorized user.'], 400);
        }
        $post = Post::find((int) $request->id);
        $post->title = $request->title;
        $post->content = $request->content;
        $post->type_id = $request->type_id;
        $post->user_id = $request->user_id;
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
            'id' => 'required|exists:App\Models\Post|max:255',
        ]);
        $post = Post::find((int) $request->id);
        if (empty(auth('api')->user()->id) || (int) $request->user_id !== auth('api')->user()->id || $post->user->id !== auth('api')->user()->id) {
            return response()->json(['message' => 'The given post user details did not match with authorized user.'], 400);
        }
        return response()->json($post->delete());
    }
}
