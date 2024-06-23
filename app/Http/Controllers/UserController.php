<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only([
            'index',
            'show',
            'search',
            'update',
            'destroy',
            'logout'
        ]);
        $this->middleware('admin')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'data' => User::get([
                'id',
                'username',
                'email',
                'created_at',
                'updated_at'
            ])
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'username' => 'required|string|min:4|unique:users',
            'email' => 'required|email|unique:users',
            'password' =>
            [
                'required',
                'confirmed',
                Password::min(7)
                        ->mixedCase()
                        ->numbers()
                        ->symbols()
            ],
        ]);

        $fields['password'] = bcrypt($fields['password']);

        $user = User::create($fields);

        $token = $user->createToken('LaraLance', ['*'], Carbon::now()->addDay());

        return response()->json([
            'message' => 'User registered.',
            'data' =>
            [
                'username' => $user->username,
                'email' => $user->email,
                'token' => $token->plainTextToken
            ]
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json([
            'data' => User::findOrFail($id, [
                'id',
                'username',
                'email',
                'created_at',
                'updated_at'
            ])
        ]);
    }

    /**
     * Display a filtered listing of the resource.
     */
    public function search(Request $request)
    {
        $input = trim($request->input('q')) ? $request->input('q') : null;

        if ($input == null)
        {
            return response()->json([
                'message' => 'Search by username or email.'
            ]);
        }

        return response()->json([
            'data' => User::where('username', 'like', "%{$input}%")
                          ->orWhere('email', 'like', "%{$input}%")
                          ->get([
                            'id',
                            'username',
                            'email',
                            'created_at',
                            'updated_at'
                          ])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $fields = $request->validate([
            'username' => 'string|min:4|unique:users',
            'email' => 'email|unique:users',
            'password' =>
            [
                'confirmed',
                Password::min(7)
                        ->letters()
                        ->mixedCase()
                        ->numbers()
                        ->symbols()
            ],
        ]);

        if (array_key_exists('password', $fields))
        {
            $fields['password'] = bcrypt($fields['password']);
        }

        $user = $request->user();

        $user->update($fields);

        return response()->json([
            'message' => 'User updated.',
            'data' => User::findOrFail($user->id, [
                'id',
                'username',
                'email'
            ])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        if ($request->user()->id === $user->id)
        {
            return response()->json([
                'message' => "You cannot delete your own account!"
            ], 422);
        }

        $user->delete();

        return response()->json([
            'message' => 'User deleted.',
            'data' =>User::findOrFail($user->id, [
                'id',
                'username',
                'email'
            ])
        ]);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('username', $fields['username'])->first();

        if ($user and Hash::check($fields['password'], $user->password))
        {
            $token = $user->createToken('LaraLance', ['*'], Carbon::now()->addDay());

            return response()->json([
                'message' => 'User logged in.',
                'data' =>
                [
                    'username' => $user->username,
                    'token' => $token->plainTextToken
                ]
            ]);
        }
        else
        {
            return response()->json([
                'message' => 'Invalid credentials!'
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'User logged out.'
        ]);
    }
}
