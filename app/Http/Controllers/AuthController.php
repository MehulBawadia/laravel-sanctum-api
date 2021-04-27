<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Login a existing user.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|email:filter|exists:users,email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $fields['email'])->first();
        if (! $user || ! Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Invalid credentials.',
            ], 401);
        }

        $token = $user->createToken('my-app-token')->plainTextToken;

        return response([
            'message' => 'User logged in successfully.',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email:filter|unique:users,email',
            'password' => 'required|string|confirmed',
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
        ]);

        $token = $user->createToken('my-app-token')->plainTextToken;

        return response([
            'message' => 'User registered successfully.',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * Logging out the user.
     *
     * @param  \Illuminate\Http\Request $request [description]
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return response([
            'message' => 'Logged out.'
        ]);
    }
}
