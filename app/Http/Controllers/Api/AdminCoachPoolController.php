<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CoachPoolAssignment;
use App\Models\SwimCoach;
use Illuminate\Http\Request;

class AdminCoachPoolController extends Controller
{
    // GET /api/admin/coaches/{coachId}/pools
    // Melihat daftar kolam yang dipegang oleh coach tertentu
    public function index($coachId)
    {
        $coach = SwimCoach::with('pools')->findOrFail($coachId);

        $pools = $coach->pools->map(function ($pool) {
            return [
                'assignment_id'  => $pool->pivot->id,
                'pool_id'        => $pool->id,
                'pool_name'      => $pool->pool_name,
                'is_primary'     => (bool) $pool->pivot->is_primary,
                'effective_from' => $pool->pivot->effective_from,
            ];
        });

        return response()->json($pools);
    }

    // POST /api/admin/coach-pools
    // Menugaskan coach ke kolam baru (Assign)
    public function store(Request $request)
    {
        $data = $request->validate([
            'coach_id'       => 'required|exists:swim_coaches,id',
            'pool_id'        => 'required|exists:pool_locations,id',
            'is_primary'     => 'boolean',
            'effective_from' => 'nullable|date',
        ]);

        // Cek Duplikat: Apakah coach ini SUDAH ditugaskan di kolam ini?
        $exists = CoachPoolAssignment::where('coach_id', $data['coach_id'])
            ->where('pool_id', $data['pool_id'])
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Coach sudah ditugaskan di kolam ini'], 422);
        }

        $assignment = CoachPoolAssignment::create([
            'coach_id'       => $data['coach_id'],
            'pool_id'        => $data['pool_id'],
            'is_primary'     => $data['is_primary'] ?? false,
            'effective_from' => $data['effective_from'] ?? now()->toDateString(),
        ]);

        return response()->json($assignment, 201);
    }

    // DELETE /api/admin/coach-pools/{id}
    public function destroy($id)
    {
        // Hapus berdasarkan ID Assignment 
        $assignment = CoachPoolAssignment::findOrFail($id);
        $assignment->delete();

        return response()->json(['message' => 'Akses kolam berhasil dicabut']);
    }
}
