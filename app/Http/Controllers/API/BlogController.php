<?php

namespace App\Http\Controllers\API;

use App\Models\Post;
use App\Models\Type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tag;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $published = Type::where('tag', 'published')->first();
        if (empty($published)) {
            return $this->unknownErrorResponse();
        }


        $paginator = Post::where('type_id', $published->id)
            ->latest('published_at')
            ->paginate(10);

        $posts = $paginator->makeHidden(['published', 'user', 'created_at', 'updated_at', 'first_tag', 'html', 'meta_description', 'meta_title']);

        foreach ($posts as $post) {
            $post->tags = $post->tags()
                ->oldest()
                ->get()
                ->makeHidden(['id', 'created_at', 'updated_at']);
            $post->author = $post->author()->get()->makeHidden(['email', 'role', 'created_at', 'updated_at'])->first();
        }

        $paginator->data = $posts;

        return response()->json($paginator);
    }


    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)

    {
        if ($post->exists && $post->isPublished()) {
            $post->makeHidden(['published', 'user', 'created_at', 'updated_at', 'first_tag']);
            $post->tags = $post->tags()
                ->oldest()
                ->get()
                ->makeHidden(['id', 'created_at', 'updated_at']);
            $post->author = $post->author()->get()->makeHidden(['email', 'role', 'created_at', 'updated_at'])->first();
            return response()->json($post);
        }
        abort(404);
    }


    /**
     * Get all tags.
     *
     * @return \Illuminate\Http\Response
     */
    public function tag()
    {
        $tags = Tag::oldest()
            ->get()
            ->makeHidden(['id', 'created_at', 'updated_at']);
        if (!empty($tags)) {
            return response()->json($tags);
        }

        abort(404);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
