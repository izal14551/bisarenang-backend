<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SwimCoach;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminCoachController extends Controller
{
    // GET /api/admin/coaches
    public function index()
    {
        $coaches = SwimCoach::with('user')->orderBy('id', 'desc')->get();

        return response()->json($coaches);
    }

    // GET /api/admin/coaches/{coach}
    public function show(SwimCoach $coach)
    {
        $coach->load('user');

        return response()->json($coach);
    }

    // POST /api/admin/coaches
    public function store(Request $request)
    {
        $data = $request->validate([
            // akun login coach (kalau coach punya login)
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|string|min:6',
            // profil coach
            'phone_number' => 'nullable|string|max:20',
            'is_active'    => 'nullable|boolean',
        ]);

        // 1) buat user role coach
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'coach',
        ]);

        // 2) buat swim_coach
        $coach = SwimCoach::create([
            'user_id'      => $user->id ?? null, // kalau kolom user_id sudah ada
            'full_name'    => $data['name'],
            'phone_number' => $data['phone_number'] ?? null,
            'is_active'    => $data['is_active'] ?? true,
        ]);

        $coach->load('user');

        return response()->json([
            'user'  => $user,
            'coach' => $coach,
        ], 201);
    }

    // PUT /api/admin/coaches/{coach}
    public function update(Request $request, SwimCoach $coach)
    {
        $coach->load('user');
        $user = $coach->user;

        $data = $request->validate([
            'name'         => 'sometimes|string|max:255',
            'email'        => 'sometimes|email|unique:users,email,' . ($user?->id),
            'password'     => 'sometimes|nullable|string|min:6',
            'phone_number' => 'sometimes|nullable|string|max:20',
            'is_active'    => 'sometimes|boolean',
        ]);

        // update user
        if ($user) {
            if (isset($data['name'])) {
                $user->name = $data['name'];
            }
            if (isset($data['email'])) {
                $user->email = $data['email'];
            }
            if (!empty($data['password'])) {
                $user->password = Hash::make($data['password']);
            }
            $user->save();
        }

        // update coach profile
        if (isset($data['name'])) {
            $coach->full_name = $data['name'];
        }
        if (array_key_exists('phone_number', $data)) {
            $coach->phone_number = $data['phone_number'];
        }
        if (array_key_exists('is_active', $data)) {
            $coach->is_active = $data['is_active'];
        }

        $coach->save();

        $coach->load('user');

        return response()->json($coach);
    }

    // DELETE /api/admin/coaches/{coach}
    public function destroy(SwimCoach $coach)
    {
        $coach->load('user');

        if ($coach->user) {
            $coach->user->delete();
        } else {
            $coach->delete();
        }

        return response()->json([
            'message' => 'Coach deleted',
        ]);
    }
}
