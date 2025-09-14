<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Register (owner or employee) - only allow creating employee via API if owner token used; seed will create owner
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'=>'required|string',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|string|min:6',
            'role'=>'nullable|string'
        ]);

        $user = User::create([
            'name'=>$data['name'],
            'email'=>$data['email'],
            'role'=>$data['role'] ?? 'employee',
            'password'=>Hash::make($data['password']),
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json(['user'=>$user, 'token'=>$token], 201);
    }

    // Login
    public function login(Request $request)
    {
        $data = $request->validate([
            'email'=>'required|email',
            'password'=>'required|string'
        ]);

        $user = User::where('email', $data['email'])->first();
        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages(['email' => ['The provided credentials are incorrect.']]);
        }

        // Revoke previous tokens (optional)
        $user->tokens()->delete();

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json(['user'=>$user, 'token'=>$token]);
    }

    // Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message'=>'Logged out']);
    }
}