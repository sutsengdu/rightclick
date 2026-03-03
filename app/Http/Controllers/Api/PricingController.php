<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PricingResource;
use App\Models\Pricing;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PricingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Pricing::query();

        // Sorting
        $sortBy = $request->get('sort_by', 'hour');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $perPage = $request->get('per_page', 15);
        $pricing = $query->paginate($perPage);

        return PricingResource::collection($pricing);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'hour' => 'required|integer|min:0',
            'price' => 'required|integer|min:0',
        ]);

        $pricing = Pricing::create($validated);

        return response()->json([
            'message' => 'Pricing created successfully',
            'data' => new PricingResource($pricing)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pricing $pricing): JsonResponse
    {
        return response()->json([
            'data' => new PricingResource($pricing)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pricing $pricing): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'hour' => 'sometimes|integer|min:0',
            'price' => 'sometimes|integer|min:0',
        ]);

        $pricing->update($validated);

        return response()->json([
            'message' => 'Pricing updated successfully',
            'data' => new PricingResource($pricing)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pricing $pricing): JsonResponse
    {
        $pricing->delete();

        return response()->json([
            'message' => 'Pricing deleted successfully'
        ], 200);
    }
}
