<?php

namespace App\Http\Controllers\API;

use Validator;
use App\Models\Tag;
use App\Models\Post;
use App\Models\Type;
use App\Models\User;
use App\Models\PostMeta;
use App\Traits\PostsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use App\Models\PostTypes;
use Illuminate\Support\Facades\Storage;
use Cviebrock\EloquentSluggable\Services\SlugService;

class PostController extends Controller
{


    const  POST_VALIDATION_RULES = [
        "published" => "required|boolean",
        "title" => "required|string|min:3|max:255",
        "slug" => "required|string|min:3|max:255",
        "html" => "nullable|string|min:3|max:30000",
        "custom_excerpt" => "nullable|string|max:4000",
        "meta_title" => "nullable|string|max:255|required_with:meta_description",
        "meta_description" => "nullable|string|max:4000",
        "featured" => "boolean",
        "tags" => "nullable|array",
        "tags[*]name" => "string|max:255",
        "tags[*]slug" => "required_with:tags[*]name|string|max:255",
        "featured_image_file" => "nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5000",
        "author_id" => "required|integer|exists:App\Models\User,id|max:255",
        "type" => "required|string|in:" . PostTypes::Page . "," . PostTypes::Post . "|max:255"
    ];


    use PostsTrait;

    /**
     * Create a new PostController instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Get a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'per_page' => 'numeric|max:255',
            'published' => 'numeric|in:1,0|max:255',
            'type' => 'required|string|in:' . PostTypes::Post . ',' . PostTypes::Page . '|max:255'
        ]);


        $this->authorize('index', Post::class);
        $per_page = $request->filled('per_page') ? (int)$request->per_page : 10;

        $where = [];
        $where['type'] = $request->type;
        if ($request->has('published')) {
            $where['published'] = $request->published;
        }


        if ($request->user()->cannot('indexAll', Post::class)) {
            $where['user_id'] = $request->user()->id;
        }

        $paginator = Post::select('id', 'title', 'featured', 'published', 'user_id', 'created_at', 'updated_at')->where($where)->latest()->paginate($per_page);
        return response()->json($paginator);
    }


    /**
     * Get a single of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPost(Post $post)
    {
        if (!$post->exists || $post->isPage()) {
            return response()->json(["message" => "Post not found."], 404);
        }

        $this->authorize('view', $post);

        $post->tags;
        return response()->json($post);
    }

    /**
     * Get a post model.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPage(Post $post)
    {
        if (!$post->exists || !$post->isPage()) {
            return response()->json(["message" => "Post not found."], 404);
        }

        $this->authorize('view', $post);

        $post->tags;
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
        $postData = $this->decode_json_array($request->all());

        Validator::make($postData, self::POST_VALIDATION_RULES)->validate();

        $this->authorize('create', Post::class);

        $post = $this->get();

        if ($postData['published'] == true) {
            $post->published_at = now();
        }

        $post->setAttribute('title', $postData["title"]);
        $post->setAttribute('html', $postData["html"] ?? null);
        $post->setAttribute('custom_excerpt', $postData["custom_excerpt"] ?? null);
        $post->setAttribute('featured', $postData["featured"] ?? false);
        $post->setAttribute('published', $postData["published"]);
        $post->setAttribute('type', $postData["type"]);
        $post->setAttribute('user_id', $request->user()->id);
        if (($request->user()->isSuperAdmin() || $request->user()->isAdmin())) {
            $post->setAttribute('author_id', $postData['author_id']);
        } else {
            $post->setAttribute('author_id', $request->user()->id);
        }
        if (
            $request->hasFile('featured_image_file') &&
            $request->file('featured_image_file')->isValid()
        ) {
            // Store new featured image
            $path = $request->file('featured_image_file')->store('images', ['disk' => 'public']);
            if ($path) {
                $post->featured_image = $path;
            }
        }

        // Generate unique slug
        $post->slug = SlugService::createSlug($post, "slug", $postData["slug"]);

        if ($post->save()) {
            $post->user;
            $post->author;

            $post->tags()->detach();
            $tags = $this->getTagModels(!empty($postData['tags']) ? $postData['tags'] : []);
            $post->tags = $post->tags()->saveMany($tags);

            if (!empty($postData["meta_title"])) {
                // Settings meta title and meta description
                $meta = new PostMeta();
                $meta->title = $postData["meta_title"];
                $meta->description = $postData["meta_description"] ?? null;
                $post->meta = $post->meta()->save($meta);
            }

            return response()->json($post);
        } else {

            // Delete featured image if post model failed to save
            Storage::disk('public')->delete($path);
        }
        return $this->unknownErrorResponse();
    }


    /**
     * Update existing post.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $rules = self::POST_VALIDATION_RULES;
        $rules['id'] = 'required|exists:App\Models\Post,id|max:255';
        $rules['published_at'] = 'nullable|date|before_or_equal:' . now() . '|max:255';
        $postData = $this->decode_json_array($request->all());
        Validator::make($postData, $rules)->validate();

        $post = $this->get($postData['id']);

        if (!$post->exists) {
            return $this->unknownErrorResponse();
        }
        $this->authorize('update', $post);

        if ($postData["published"] == true) {
            $post->published_at = $postData['published_at'] ?? now();
        } else {
            $post->published_at = null;
        }

        $post->setAttribute('title', $postData["title"]);
        $post->setAttribute('html', $postData["html"] ?? null);
        $post->setAttribute('custom_excerpt', $postData["custom_excerpt"] ?? null);
        $post->setAttribute('featured', $postData["featured"] ?? false);
        $post->setAttribute('type', $postData["type"]);
        $post->setAttribute('published', $postData["published"]);
        if (($request->user()->isSuperAdmin() || $request->user()->isAdmin())) {
            $post->setAttribute('author_id', $postData['author_id']);
        } else {
            $post->setAttribute('author_id', $request->user()->id);
        }


        if (
            $request->hasFile('featured_image_file') &&
            $request->file('featured_image_file')->isValid()
        ) {
            // Store new featured image
            $path = $request->file('featured_image_file')->store('images', ['disk' => 'public']);
            if ($path) {
                if (!empty($post->featured_image)) {
                    // Delete existing featured image
                    Storage::disk('public')->delete($post->featured_image);
                }
                $post->featured_image = $path;
            }
        } elseif (
            empty($postData["featured_image"])
        ) {
            // Delete existing featured image if no input or file present in request
            Storage::disk('public')->delete($post->featured_image);
            $post->featured_image = null;
        }


        // Generate unique slug
        $post->slug = SlugService::createSlug($post, "slug", $postData["slug"]);

        if ($post->saveOrFail()) {
            $post->user;
            $post->author;
            if (!empty($postData['meta_title'])) {
                $meta = $post->meta ? $post->meta : new PostMeta();
                $meta->title = $postData['meta_title'];
                $meta->description = $postData["meta_description"] ?? null;
                $post->meta = $post->meta()->save($meta);
            } else {
                $post->meta()->delete();
            }
            $post->tags()->detach();
            $tags = $this->getTagModels(!empty($postData['tags']) ? $postData['tags'] : []);
            $post->tags = $post->tags()->saveMany($tags);

            return response()->json($post);
        } else {
            // Delete featured image if post model failed to save
            Storage::disk('public')->delete($path);
        }

        return $this->unknownErrorResponse();
    }



    /**
     * @param Post $post
     * @param array $tags
     * @return Tag[] $tagsModels
     */
    private function getTagModels(array $tags = null)
    {
        $tagModels = [];
        if (!empty($tags)) {
            // Syncing existing and new tags to post model
            foreach ($tags as $key => $value) {
                $tagModels[] = Tag::firstOrCreate(['slug' => $value->slug], ['name' => $value->name]);
            }
        }

        return $tagModels;
    }



    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        if (Storage::disk('public')->exists($post->featured_image)) {
            Storage::disk('public')->delete($post->featured_image);
        }

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
