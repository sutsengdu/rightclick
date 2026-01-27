<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOutcomeRequest;
use App\Http\Requests\UpdateOutcomeRequest;
use App\Http\Resources\OutcomeResource;
use App\Models\Outcome;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Request;

class OutcomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Outcome::query();

        // Filter by description
        if ($request->has('description')) {
            $query->where('description', 'like', '%' . $request->description . '%');
        }

        // Filter by price range
        if ($request->has('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->has('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        // Filter by date range
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $outcomes = $query->paginate($perPage);

        return OutcomeResource::collection($outcomes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOutcomeRequest $request): JsonResponse
    {
        $outcome = Outcome::create($request->validated());

        return response()->json([
            'message' => 'Outcome created successfully',
            'data' => new OutcomeResource($outcome)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Outcome $outcome): JsonResponse
    {
        return response()->json([
            'data' => new OutcomeResource($outcome)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOutcomeRequest $request, Outcome $outcome): JsonResponse
    {
        $outcome->update($request->validated());

        return response()->json([
            'message' => 'Outcome updated successfully',
            'data' => new OutcomeResource($outcome)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Outcome $outcome): JsonResponse
    {
        $outcome->delete();

        return response()->json([
            'message' => 'Outcome deleted successfully'
        ], 200);
    }

    /**
     * Get total outcomes for a date range
     */
    public function total(Request $request): JsonResponse
    {
        $query = Outcome::query();

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $total = $query->sum('price');
        $count = $query->count();

        return response()->json([
            'total' => (float) $total,
            'count' => $count,
            'date_from' => $request->get('date_from'),
            'date_to' => $request->get('date_to'),
        ]);
    }
}
