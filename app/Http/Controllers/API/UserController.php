<?php

namespace App\Http\Controllers\API;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Cviebrock\EloquentSluggable\Services\SlugService;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('index', User::class);
        $users = User::all()->makeVisible(['created_at', 'updated_at', 'email']);
        return response()->json($users);
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:App\Models\User,email|max:255',
            'slug' => 'required|string|unique:App\Models\User,slug|max:255',
            'website' => 'string|url|max:255',
            'description' => 'string|max:255',
            'password' => 'required|string|min:8|max:255',
            'role' => 'required|string|exists:App\Models\Role,slug|max:255'
        ]);
        $role = Role::whereSlug($request->role)->first();
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->has('website')) {
            $user->website = $request->website;
        }
        if ($request->has('description')) {
            $user->description = $request->description;
        }
        $user->password = Hash::make($request->password);
        $user->role_id = $role->id;

        // Generate unique slug
        $user->slug = SlugService::createSlug($user, "slug", $request->slug);

        $this->authorize('store', $user);
        if ($user->saveOrFail()) {
            $user->makeVisible(['email']);
            return response()->json($user);
        }
        return $this->unknownErrorResponse();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        if (!$user->exists) {
            return response()->json(["message" => "User not found."], 404);
        }
        $user->makeVisible('email');

        $this->authorize('view', $user);

        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if (!$user->exists) {
            return response()->json(["message" => "User not found."], 404);
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:App\Models\User,email,' . $user->id . '|max:255',
            'slug' => 'required|string|unique:App\Models\User,slug,' . $user->id . '|max:255',
            'website' => 'string|url|max:255',
            'description' => 'string|max:255',
            'password' => 'string|min:8|max:255',
            'role' => 'required|string|exists:App\Models\Role,slug|max:255'
        ]);


        $role = Role::whereSlug($request->role)->first();
        $user->role = $role;
        if ($request->has('website')) {
            $user->website = $request->website;
        } else {
            $user->website = null;
        }
        if ($request->has('description')) {
            $user->description = $request->description;
        } else {
            $user->description = null;
        }
        $user->name = $request->name;
        $user->email = $request->email;
        // Generate unique slug
        $user->slug = SlugService::createSlug($user, "slug", $request->slug);
        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }
        // unset($user->to);
        $this->authorize('update', $user);
        if ($user->saveOrFail()) {
            $user->makeVisible(['email']);
            return response()->json($user);
        }
        return $this->unknownErrorResponse();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if (!$user->exists) {
            return response()->json(["message" => "User not found."], 404);
        }

        $this->authorize('delete', $user);

        return response()->json($user->delete());
    }
}
