<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            "name" => "required|string",
            "email" => "required|unique:users",
            "password" => "required|string"
        ]);

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
        ]);

        $token = $user->createToken("auth_token")->plainTextToken;

        return response()->json([
            "message" => "Registred Successfully",
            "user" => $user,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            "email" => "required",
            "password" => "required",
        ]);

        $user = User::where("email", $request->email)->first();

        if (!$user) {
            return response()->json([
                "message" => "Email is incorrect"
            ]);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                "message" => "Password is incorrect"
            ]);
        }

        $token = $user->createToken("auth_token")->plainTextToken;

        return response()->json([
            "message" => "Logged Successfully",
            "user" => $user,
            "token" => $token,
        ], 201);
    }

    public function logout(Request $request) {
        $request->user()->tokens()->delete();

        return response()->json([
            "message" => "Logouted Successfully"
        ], 201);
    }
}
