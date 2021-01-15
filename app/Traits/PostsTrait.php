<?php

namespace App\Traits;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;

/**
 * Trait to provide helper methods to PostController
 */
trait PostsTrait
{

    /**
     * Fetch all posts as models from storage.
     *
     * @param bool $mergeForeignAtts
     * @return Collection
     */
    public function getAll(bool $mergeForeignAtts = null): Collection
    {
        $posts = Post::all();
        if ($mergeForeignAtts !== null) {
            foreach ($posts as $post) {
                $post->user = $post->user;
                $post->type = $post->type;
            }
        }
        return $posts;
    }

    /**
     * Find a post or create a new one.
     *
     * @param int $pk
     * @return App\Models\Post
     */
    function get(int $pk = null): Post
    {
        if (empty($pk)) {
            $post = new Post();
        } else {
            $post = Post::findOrFail($pk);
        }
        return $post;
    }


    /**
     * @param array $arr
     * @return array
     */
    function decode_json_array(array $arr): array
    {
        $newArray = [];
        foreach ($arr as $key => $value) {
            $decodedValue = json_decode($value);
            if (json_last_error() == JSON_ERROR_NONE) {
                $newArray[$key] = $decodedValue;
            } else {
                $newArray[$key] = $value;
            }
        }
        return $newArray;
    }
}
