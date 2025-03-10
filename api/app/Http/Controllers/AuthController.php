<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
        $request->validate([
            "email" => ['required'],
            "password" => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Email or password are incorrect.'], 404);
        }

        return response()->json(["user" => $user]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => "You are logged out."], 200);
    }
}
