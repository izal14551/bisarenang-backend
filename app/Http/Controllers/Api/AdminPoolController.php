<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePoolRequest;
use App\Http\Requests\UpdatePoolRequest;
use App\Models\PoolLocation;
use Illuminate\Http\Request;

class AdminPoolController extends Controller
{
    // GET /api/admin/pools
    public function index()
    {
        $pools = PoolLocation::orderBy('id', 'desc')->get();
        return response()->json($pools);
    }

    // POST /api/admin/pools
    public function store(StorePoolRequest $request)
    {
        $data = $request->validated();

        $pool = PoolLocation::create([
            'pool_name'    => $data['pool_name'],
            'is_available' => $data['is_available'] ?? true,
        ]);

        return response()->json($pool, 201);
    }

    // GET /api/admin/pools/{pool}
    public function show(PoolLocation $pool)
    {
        return response()->json($pool);
    }

    // PUT /api/admin/pools/{pool}
    public function update(UpdatePoolRequest $request, PoolLocation $pool)
    {
        $data = $request->validated();

        $pool->update($data);

        return response()->json($pool);
    }

    // DELETE /api/admin/pools/{pool}
    public function destroy(PoolLocation $pool)
    {
        $pool->delete();
        return response()->json(['message' => 'Pool deleted']);
    }
}
