<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SwimClass;
use Illuminate\Http\Request;

class SwimClassController extends Controller
{
    public function index(Request $request)
    {
        $query = SwimClass::with(['pool', 'schedules'])
            ->where('is_active', true);

        if ($request->has('pool_id')) {
            $query->where('pool_id', $request->pool_id);
        }

        $classes = $query->get();

        return response()->json($classes);
    }

    public function show($id)
    {
        $class = SwimClass::with(['pool', 'schedules'])
            ->where('is_active', true)
            ->findOrFail($id);

        return response()->json($class);
    }
}
