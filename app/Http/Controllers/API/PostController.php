<?php

namespace App\Http\Controllers\API;

use App\Models\Post;
use App\Models\Tag;
use App\Traits\PostsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;

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
        // return response()->json($request->all());
        $request->validate([
            'type_id' => 'required|integer|exists:App\Models\Type,id|max:255',
            'user_id' => 'required|integer|exists:App\Models\User,id|max:255',
            'title' => 'required|string|min:3|max:255',
            'slug' => 'required|string|min:3|max:255',
            'html' => 'required|string|min:3|max:30000',
            'featured_image' => 'string|nullable|max:255',
            'custom_excerpt' => 'string|nullable|max:4000',
            'meta_title' => 'string|nullable|max:255',
            'meta_description' => 'string|nullable|max:4000',
            'featured' => 'boolean',
            'tags' => 'required|array',
            'tags.*.name' => 'string|max:255',
            'tags.*.slug' => 'required_with:tags.*.name|string|max:255',
        ]);

        $this->authorize('create', [Post::class, $request]);


        $post = $this->manipulate($this->get(), $request, ['type_id', 'title', 'slug', 'html', 'featured_image', 'custom_excerpt', 'featured']);

        if ($post->save()) {

            $tags = [];

            foreach ($request->tags as $tag) {
                $tags[] = Tag::firstOrCreate(['name' => $tag['name']], ['slug' => $tag['slug']]);
            }
            $tags = collect($tags);
            $post->tags()->sync($tags->pluck('id'));
            
            $post->tags = $post->tags()->get();
            $allTags = Tag::all();

            return response()->json(["post" => $post, "tags" => $allTags]);
        }

        return response()->json(["message" => "An unknown error has occurred while saving post!"], 500);
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

        $this->authorize('update', [Post::class, $post, $request]);

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

        $this->authorize('delete', [Post::class, $post]);

        return response()->json($post->delete());
    }


    /**
     * Upload an image to the server.
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadImage(Request $request)
    {
        $request->validate(
            ['file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5000']
        );

        $path = $request->file('file')->store('images', ['disk' => 'public']);
        return json_encode(['location' => URL::to('/') . '/storage/' . $path]);
    }


    /**
     * Send all the tags for post creation.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexTags(Request $request)
    {
        $tags = Tag::all();
        return response()->json($tags);
    }
}
