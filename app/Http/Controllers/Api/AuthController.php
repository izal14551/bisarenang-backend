<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\SwimMember;

class AuthController extends Controller
{
    // POST /api/register
    /*
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed', // butuh field password_confirmation
            'role'     => 'required|in:admin,member',
            'phone_number'   => 'required|string|max:20',
            'date_of_birth'  => 'required|date',
        ]);
        $role = $data['role'] ?? 'member';

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => $role,

        ]);

        if ($role === 'member') {
            $member = SwimMember::create([
                'user_id'      => $user->id,
                'full_name'    => $data['name'],
                'phone_number' => $data['phone_number'] ?? null,
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'is_active'    => true,
            ]);
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'member' => $member,
            'token' => $token,
        ], 201);
    }
    */

    // POST /api/login
    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Optional: revoke tokens lama
        $user->tokens()->delete();

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token,
        ]);
    }

    // GET /api/me
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    // POST /api/logout
    public function logout(Request $request)
    {
        // Hapus token yang sedang dipakai
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out',
        ]);
    }
}
