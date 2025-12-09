<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SwimMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminMemberController extends Controller
{
    // GET /api/admin/members
    public function index()
    {
        $members = SwimMember::with('user')->orderBy('id', 'desc')->get();

        return response()->json($members);
    }

    // GET /api/admin/members/{member}
    public function show(SwimMember $member)
    {
        $member->load('user');

        return response()->json($member);
    }

    // POST /api/admin/members
    public function store(Request $request)
    {
        $data = $request->validate([
            // data akun login
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email',
            'password'       => 'required|string|min:6',
            // profil member
            'phone_number'   => 'nullable|string|max:20',
            'date_of_birth'  => 'nullable|date',
            'is_active'      => 'nullable|boolean',
        ]);

        // 1) buat user role member
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'member',
        ]);

        // 2) buat swim_member terkait user
        $member = SwimMember::create([
            'user_id'      => $user->id,
            'full_name'    => $data['name'],
            'phone_number' => $data['phone_number'] ?? null,
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'is_active'    => $data['is_active'] ?? true,
        ]);

        $member->load('user');

        return response()->json([
            'user'   => $user,
            'member' => $member,
        ], 201);
    }

    // PUT /api/admin/members/{member}
    public function update(Request $request, SwimMember $member)
    {
        $member->load('user');
        $user = $member->user;

        $data = $request->validate([
            'name'           => 'sometimes|string|max:255',
            'email'          => 'sometimes|email|unique:users,email,' . ($user?->id),
            'password'       => 'sometimes|nullable|string|min:6',
            'phone_number'   => 'sometimes|nullable|string|max:20',
            'date_of_birth'  => 'sometimes|nullable|date',
            'is_active'      => 'sometimes|boolean',
        ]);

        // update user kalau ada
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

        // update profil member
        if (isset($data['name'])) {
            $member->full_name = $data['name'];
        }
        if (array_key_exists('phone_number', $data)) {
            $member->phone_number = $data['phone_number'];
        }
        if (array_key_exists('date_of_birth', $data)) {
            $member->date_of_birth = $data['date_of_birth'];
        }
        if (array_key_exists('is_active', $data)) {
            $member->is_active = $data['is_active'];
        }

        $member->save();

        $member->load('user');

        return response()->json($member);
    }

    // DELETE /api/admin/members/{member}
    public function destroy(SwimMember $member)
    {
        $member->load('user');

        // kalau mau hard delete user juga:
        if ($member->user) {
            $member->user->delete(); // jika user ga pakai softDeletes, ini hard delete
        } else {
            $member->delete(); // soft delete member saja
        }

        return response()->json([
            'message' => 'Member deleted',
        ]);
    }
}
