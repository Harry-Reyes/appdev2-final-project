<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'data' => User::all()
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
            'data' => User::find($id)
        ]);
    }

    /**
     * Display a filtered listing of the resource.
     */
    public function search(Request $request)
    {
        $input = null;

        if ($request->input('username'))
        {
            $input = trim($request->input('username'));
        }
        else if ($request->input('email'))
        {
            $input = trim($request->input('email'));
        }
        if ($input == null)
        {
            return response()->json([
                'message' => 'Search by username or email.'
            ]);
        }
        return response()->json([
            'data' => User::where('username', 'like', "%{$input}%")
                          ->orWhere('email', 'like', "%{$input}%")
                          ->get()
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
            'data' =>
            [
                'username' => $user->username,
                'email' => $user->email
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        $user->delete();
        return response()->json([
            'message' => 'User deleted.',
            'data' => $user
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
