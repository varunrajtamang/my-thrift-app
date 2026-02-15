<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StoreResource;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    // PUBLIC: list all active stores
    public function index()
    {
        $stores = Store::where('is_active', true)->latest()->get();

        return StoreResource::collection($stores);
    }

    // AUTH: fetch own store
    public function myStore(Request $request)
    {
        $store = Store::where('user_id', $request->user()->id)->first();

        if (! $store) {
            return response()->json([
                'message' => 'Store not found'
            ], 404);
        }

        return new StoreResource($store);
    }

    // AUTH: create store (FREE)
    public function store(Request $request)
    {
        $user = $request->user();

        // One store per user
        if (Store::where('user_id', $user->id)->exists()) {
            return response()->json([
                'message' => 'You already have a store'
            ], 409);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'slug' => 'required|string|max:100|unique:stores,slug',
            'description' => 'nullable|string',
            'logo_path' => 'nullable|string',
            'cover_image_path' => 'nullable|string',
            'store_location' => 'nullable|string|max:255',
        ]);

        $store = Store::create([
            ...$validated,
            'user_id' => $user->id,
            'is_active' => true,
        ]);

        return new StoreResource($store);
    }
}
