<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PoolLocation;

class PoolController extends Controller
{
    public function index()
    {
        $pools = PoolLocation::where('is_available', true)->get();

        return response()->json($pools);
    }
}
