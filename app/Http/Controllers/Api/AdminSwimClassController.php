<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SwimClass;
use Illuminate\Http\Request;

class AdminSwimClassController extends Controller
{
    // GET /api/admin/classes
    public function index()
    {
        $classes = SwimClass::with('pool')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($classes);
    }

    // POST /api/admin/classes
    public function store(Request $request)
    {
        $data = $request->validate([
            'pool_id'       => 'required|exists:pool_locations,id',
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'schedule_type' => 'required|string',
            'max_capacity'  => 'required|integer|min:1',
            'is_active'     => 'boolean',
        ]);

        $swimClass = SwimClass::create([
            'pool_id'       => $data['pool_id'],
            'name'          => $data['name'],
            'description'   => $data['description'] ?? '',
            'schedule_type' => $data['schedule_type'],
            'max_capacity'  => $data['max_capacity'],
            'is_active'     => $data['is_active'] ?? true,
        ]);

        return response()->json($swimClass, 201);
    }

    // PUT /api/admin/classes/{swimClass}
    public function update(Request $request, SwimClass $swimClass)
    {
        $data = $request->validate([
            'pool_id'       => 'sometimes|exists:pool_locations,id',
            'name'          => 'sometimes|string|max:255',
            'description'   => 'nullable|string',
            'schedule_type' => 'sometimes|string',
            'max_capacity'  => 'sometimes|integer|min:1',
            'is_active'     => 'sometimes|boolean',
        ]);

        $swimClass->update($data);

        return response()->json($swimClass);
    }

    // DELETE /api/admin/classes/{swimClass}
    public function destroy(SwimClass $swimClass)
    {
        $swimClass->delete();
        return response()->json(['message' => 'Class deleted']);
    }
}
