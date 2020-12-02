<?php
namespace App\Traits;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
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
        $post = Post::find($pk);
        if (empty($post)) {
            $post = new Post();
        }
        return $post;
    }


    /**
     * Manipulate a given post model with the provided request data
     *
     * @param Post $post
     * @param $postData
     * @param array $inputs
     * @return App\Models\Post
     */
    function manipulate(Post $post, $postData, array $inputs)
    {
        foreach ($inputs as $input) {
            // only set attribute if it exists in request data and table
            if (!empty($postData[$input]) && Schema::hasColumn($post->getTable(), $input)) {
                $post->$input = $postData[$input];
            }
        }
        return $post;
    }
}
