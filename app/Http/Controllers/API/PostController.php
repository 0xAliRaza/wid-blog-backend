<?php

namespace App\Http\Controllers\API;

use Validator;
use App\Models\Tag;
use App\Models\Post;
use App\Models\Type;
use App\Models\PostMeta;
use App\Traits\PostsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
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
        if (empty($post->id)) {
            return response()->json(["message" => "Post not found."], 404);
        }
        $post->tags = $post->tags;
        $meta = $post->meta()->first();
        if (!empty($meta)) {
            $post->meta_title = $meta->title;
            $post->meta_description = $meta->description;
        };
        $post->status = $post->type->tag;
        $post->featured_image = URL::to('/') . Storage::url($post->featured_image);

        return response()->json($post);
    }

    /**
     * Store a resource in db.
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
            'html' => 'string|min:3|max:30000',
            'custom_excerpt' => 'string|nullable|max:4000',
            'meta_title' => 'string|nullable|max:255|required_with:meta_description',
            'meta_description' => 'string|nullable|max:4000',
            'featured' => 'boolean',
            'tags' => 'array',
            'tags.*.name' => 'string|max:255',
            'tags.*.slug' => 'required_with:tags.*.name|string|max:255',
            'user_id' => 'required|exists:App\Models\User,id|max:255',
            'featured_image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5000'
        ])->validate();

        $this->authorize('create', [Post::class, $request]);


        if (!empty($postData['id'])) {
            $post = $this->get($postData['id']);
            if ($post->isEmpty()) {
                return response()->json(["message" => "An unknown error has occurred."], 500);
            }
        } else {
            $post = $this->get();
        }


        $type = Type::where('tag', $postData["status"])->first();

        if (empty($type)) {
            return response()->json(["message" => "An unknown internal server error has occurred."], 500);
        }
        $postData["type_id"] = $type->id;

        if ($request->hasFile('featured_image_file') && $request->file('featured_image_file')->isValid()) {
            $path = $request->file('featured_image_file')->store('images', ['disk' => 'public']);
            if ($path) {
                if (!empty($post->featured_image)) {
                    Storage::disk('public')->delete($post->featured_image);
                }
                $postData["featured_image"] = $path;
            }
        }

        $post = $this->manipulate($post, $postData, ['featured_image', 'type_id', 'title', 'html', 'custom_excerpt', 'featured']);
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
                $post->featured_image = URL::to('/') . Storage::url($post->featured_image);
                // $post->featured_image =  URL::to('/') . '/storage/' . $post->featured_image;
            }

            $post->tags = $post->tags;
            $meta = $post->meta()->first();
            if (!empty($meta)) {
                $post->meta_title = $meta->title;
                $post->meta_description = $meta->description;
            };
            $post->status = $type->tag;

            return response()->json($post);
        } else {
            Storage::disk('public')->delete($path);

            return response()->json(["message" => "An unknown internal server error has occurred."], 500);
        }
        return response()->json(["message" => "An unknown internal server error has occurred."], 500);
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
