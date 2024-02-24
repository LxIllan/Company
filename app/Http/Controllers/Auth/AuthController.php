<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
	public function login(Request $request): JsonResponse
	{
		$credentials = $request->only('email', 'password');

		if (Auth::attempt($credentials)) {
			$token = Auth::user()->createToken('authToken')->plainTextToken;
			return response()->json([
				'token' => $token,
				'user' => Auth::user()
			]);
		}

		return response()->json([
			'message' => 'Invalid credentials'
		], 401);
	}

	public function logout(Request $request): JsonResponse
	{
		$request->user()->currentAccessToken()->delete();

		return response()->json([
			'message' => 'Logged out'
		]);
	}

	public function register(Request $request): JsonResponse
	{
		$request->validate([
			'name' => 'required',
			'email' => 'required|email|unique:users',
			'password' => 'required'
		]);

		$user = User::create([
			'name' => $request->name,
			'email' => $request->email,
			'password' => Hash::make($request->password)
		]);

		$token = $user->createToken('authToken')->plainTextToken;

		return response()->json([
			'token' => $token,
			'user' => $user
		]);
	}

	public function resetPassword(Request $request): JsonResponse
	{
		$request->validate([
			'email' => 'required|email'
		]);

		$user = User::where('email', $request->email)->first();

		if (!$user) {
			return response()->json([
				'message' => 'User not found'
			], 404);
		}

		$user->tokens()->delete();

		$token = $user->createToken('authToken')->plainTextToken;

		return response()->json([
			'token' => $token,
			'user' => $user
		]);
	}
}
