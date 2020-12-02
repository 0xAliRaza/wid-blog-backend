<?php

namespace App\Http\Controllers\API;

use App\Models\Post;
use App\Models\Tag;
use App\Traits\PostsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Models\PostMeta;
use App\Models\Type;
use Validator;

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
     * Get a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = $this->getAll();
        return response()->json(['posts' => $posts->toArray()]);
    }

    
    /**
     * Get a single of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPost(Post $post)
    {
        if(empty($post->id)) {
            return response()->json(["message" => "Post not found."], 404);

        }
        $post->tags = $post->tags;
        $post->meta = $post->meta()->first();
        $post->status = $post->type->tag;

        return response()->json($post);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $postData = [];
        foreach ($request->all() as $key => $value) {
            if ($key === "featured_image_file") {
                $postData[$key] = $value;
            } else {
                $postData[$key] = json_decode($value);
            }
        }


        Validator::make($postData, [
            'status' => 'required|string|in:draft,published|max:255',
            'title' => 'required|string|min:3|max:255',
            'slug' => 'required|string|min:3|max:255',
            'html' => 'required|string|min:3|max:30000',
            'custom_excerpt' => 'string|nullable|max:4000',
            'meta_title' => 'string|nullable|max:255',
            'meta_description' => 'string|nullable|max:4000',
            'featured' => 'boolean',
            'tags' => 'array',
            'tags.*.name' => 'string|max:255',
            'tags.*.slug' => 'required_with:tags.*.name|string|max:255',
            'user_id' => 'required|exists:App\Models\User,id|max:255',
            'featured_image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5000'
        ])->validate();

        $this->authorize('create', [Post::class, $request]);

        $type = Type::where('tag', $postData["status"])->first();

        if (empty($type)) {
            return response()->json(["message" => "An unknown internal server error has occurred."], 500);
        }
        $postData["type_id"] = $type->id;

        if ($request->hasFile('featured_image_file') && $request->file('featured_image_file')->isValid()) {
            $path = $request->file('featured_image_file')->store('images', ['disk' => 'public']);
            $path ? $postData["featured_image"] = $path : null;
        }

        $post = $this->manipulate($this->get(), $postData, ['featured_image', 'type_id', 'title', 'html', 'custom_excerpt', 'featured']);
        if ($post->save()) {

            if (!empty(json_decode($request->tags))) {
                $tags = [];
                foreach (json_decode($request->tags) as $key => $value) {
                    $tags[] = Tag::firstOrCreate(['name' => $value->name], ['slug' => $value->slug]);
                }
                $tags = collect($tags);
                $post->tags()->sync($tags->pluck('id'));
            }


            if (!empty($postData["meta_title"]) || !empty($postData["meta_description"])) {
                $postMeta = new PostMeta();
                if (!empty($postData["meta_title"])) {
                    $postMeta->title = $postData["meta_title"];
                }
                if (!empty($postData["meta_description"])) {
                    $postMeta->description = $postData["meta_description"];
                }
                $postMeta->post_id = (int) $post->id;
                $postMeta->saveOrFail();
            }

            if (!empty($post->featured_image)) {
                $post->featured_image = URL::to('/') . '/storage/' . $post->featured_image;
            }

            $post->tags = $post->tags;
            $post->meta = $post->meta()->first();
            $post->status = $type->tag;

            return response()->json($post);
        } else {
            Storage::delete($path);
            return response()->json(["message" => "An unknown internal server error has occurred."], 500);
        }
        return response()->json(["message" => "An unknown internal server error has occurred."], 500);
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
