<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class AuthControler extends Controller
{
    public function user() {
        
        return Auth::user();
    }

    public function register(Request $request) {
        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
    }

    public function login(Request $request) {
        // Auth::attempt([
        //     'email' => $request->email, 
        //     'password' => $request->password
        // ]);

        $credentials = $request->only('email', 'password');

        try {
            if (! $token = Auth::attempt($credentials)) {
                return response([
                    'message' => 'Invalid credentials'
                ], Response::HTTP_UNAUTHORIZED);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        $user = Auth::user()->pluck('name');

        // $token = $user->createToken('token')->plainTextToken;

        // $cookies = cookie('jwt', $token, 60 * 24);

        return response([
            'message' => 'success',
            'token' => $token,
            'name' => $user
        ]);
    }

    public function logout() {
        
    }
}
