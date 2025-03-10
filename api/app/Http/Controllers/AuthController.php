<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            "name" => ['required', 'max:100'],
            "phone" => ['required', 'min:10'],
            "email" => ['required', 'email', 'max:255', 'unique:users'],
            "password" => ['required', 'confirmed']
        ]);

        $user = User::create($fields);
        $token = $user->createToken($request->name);
        return response()->json(["user" => $user, 'token' => $token->plainTextToken], 201);
    }

    public function login(Request $request)
    {
        // 
    }

    public function logout(Request $request)
    {
        // 
    }
}
