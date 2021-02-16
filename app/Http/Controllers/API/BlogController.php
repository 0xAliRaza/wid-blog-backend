<?php

namespace App\Http\Controllers\API;

use App\Models\Tag;
use App\Models\Post;
use App\Models\PostTypes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $where = [];
        $where['type'] = PostTypes::Post;
        $where['published'] = true;

        $paginator = Post::select('title', 'slug', 'author_id', 'published_at', 'featured', 'featured_image', 'custom_excerpt')->where($where)->latest()->paginate();
        $paginator->data = $paginator->each(function ($post, $key) {
            $post->author->makeHidden(['id', 'role']);
            $post->makeHidden(['author_id', 'user']);
            $post->first_tag ? $post->first_tag->makeHidden(['created_at', 'updated_at', 'id']) : null;
        });
        return response()->json($paginator);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexPages()
    {

        $where = [];
        $where['type'] = PostTypes::Page;
        $where['published'] = true;

        $posts = Post::select('title', 'slug')->where($where)->latest()->get();
        $posts->each(function ($post, $key) {
            $post->makeHidden(['user', 'first_tag', 'meta_description', 'meta_title']);
        });
        return response()->json($posts);
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
            $post->tags->each(function ($tag) {
                $tag->makeHidden('created_at', 'updated_at', 'id');
            });
            $post->author->makeHidden(['id', 'role']);
            $post->makeHidden(['author_id', 'created_at', 'id', 'published', 'updated_at', 'user', 'first_tag']);
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
