<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = User::whereEmail($validated['email'])->first();
        if(!$user){
            throw ValidationException::withMessages([
                'email' => [__('auth.failed')],
            ]);
        }
        if(!Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'password' => [__('auth.password')],
            ]);
        }
        $token = $user->createToken($user->email)->plainTextToken;
        return response()->json([ 'accessToken' => $token ]);
    }


    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'birth_date' => 'required|date|before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
        ],[], [
            'birth_date' => __('birth_date')
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'birth_date' => $request->birth_date,
        ]);

        $token = $user->createToken($user->email)->plainTextToken;


        return response()->json([
            'message' => __('User created successfully!'),
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'birth_date' => $user->birth_date->format('Y-m-d')
            ],
            'accessToken' => $token,
        ], 201);
    }

}
