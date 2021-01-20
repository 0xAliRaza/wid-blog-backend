<?php

namespace App\Http\Controllers\API;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

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
        $users = User::select('id', 'name', 'email', 'created_at', 'updated_at', 'role_id')->get();
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
            'password' => 'required|string|min:8|max:255',
            'role' => 'required|string|exists:App\Models\Role,tag|max:255'
        ]);
        $role = Role::whereTag($request->role)->first();
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role_id = $role->id;

        $this->authorize('store', $user);
        if ($user->saveOrFail()) {
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
            'password' => 'string|min:8|max:255',
            'role' => 'required|string|exists:App\Models\Role,tag|max:255'
        ]);


        $role = Role::whereTag($request->role)->first();
        $user->role = $role;
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }
        // unset($user->to);
        $this->authorize('update', $user);
        if ($user->saveOrFail()) {
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
