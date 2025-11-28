<?php

namespace App\Http\Controllers;

use App\UserAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'name'     => 'required|string',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role'     => 'required|in:customer,organizer'
        ]);

        $user = UserAccount::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => bcrypt($request->password),
            'role'       => $request->role,
            'api_token'  => str_random(60),
        ]);

        return response()->json([
            'message'   => 'Registered successfully',
            'user'      => $user,
            'api_token' => $user->api_token
        ], 201);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $user = UserAccount::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            $user->api_token = str_random(60);
            $user->save();

            return response()->json([
                'message'   => 'Login successful',
                'user'      => $user,
                'api_token' => $user->api_token
            ]);
        }

        return response()->json(['error' => 'Invalid credentials'], 401);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $user->api_token = str_random(60);
            $user->save();
        }
        return response()->json(['message' => 'Logged out']);
    }
}