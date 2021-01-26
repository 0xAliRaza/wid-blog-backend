<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', Tag::class);
        $tags = Tag::all();
        return response()->json($tags);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('change', Tag::class);
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:App\Models\Tag,slug',
            'description' => 'nullable|string|max:255'
        ]);

        $tag = new Tag();
        $tag->name = $request->name;
        $tag->slug = $request->slug;
        $tag->description = $request->description;

        if ($tag->saveOrFail()) {
            return response()->json($tag);
        }
        return $this->unknownErrorResponse();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        $this->authorize('change', Tag::class);
        if (!$tag->exists) {
            return response()->json(["message" => "Tag not found."], 404);
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:App\Models\Tag,slug,' . $tag->id . '|max:255',
            'description' => 'nullable|string|max:255'
        ]);


        $tag->name = $request->name;
        $tag->slug = $request->slug;
        $tag->description = $request->description;

        if ($tag->saveOrFail()) {
            return response()->json($tag);
        }
        return $this->unknownErrorResponse();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        $this->authorize('change', Tag::class);
        $relatedPostIds = $tag->posts()->allRelatedIds();
        if ($relatedPostIds->count() > 0) {
            return response()->json([
                "message" => "Tag has associated posts, delete them first.",
                 "associated_post_ids" => $relatedPostIds
            ], 422);
        }
        if (!$tag->exists) {
            return response()->json(["message" => "Tag not found."], 404);
        }
        return response()->json($tag->delete());
    }
}
