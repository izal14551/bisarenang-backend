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
        $coaches = SwimCoach::with('user')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($c) {
                return [
                    'id'           => $c->id,
                    'full_name'    => $c->full_name,
                    'email'        => $c->user?->email,
                    'phone_number' => $c->phone_number,
                    'is_active'    => (bool) $c->is_active,
                ];
            });

        return response()->json($coaches);
    }

    // GET /api/admin/coaches/{coach}
    public function show(SwimCoach $coach)
    {
        $coach->load('user');

        return response()->json([
            'id'           => $coach->id,
            'full_name'    => $coach->full_name,
            'email'        => $coach->user?->email,
            'phone_number' => $coach->phone_number,
            'is_active'    => (bool) $coach->is_active,
        ]);
    }

    // POST /api/admin/coaches
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|string|min:6',
            'phone_number' => 'nullable|string|max:20',
            'is_active'    => 'nullable|boolean',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'coach',
        ]);

        $coach = SwimCoach::create([
            'user_id'      => $user->id,
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
        }

        $coach->delete();

        return response()->json([
            'message' => 'Coach deleted',
        ]);
    }
}
