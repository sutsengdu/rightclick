<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInventoryRequest;
use App\Http\Requests\UpdateInventoryRequest;
use App\Http\Resources\InventoryResource;
use App\Models\Inventory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Inventory::query();

        // Filter by type
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Filter by item name
        if ($request->has('item_name')) {
            $query->where('item_name', 'like', '%' . $request->item_name . '%');
        }

        // Filter by low stock (qty less than threshold)
        if ($request->has('low_stock')) {
            $threshold = $request->get('low_stock_threshold', 10);
            $query->where('qty', '<', $threshold);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'id');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $inventories = $query->paginate($perPage);

        return InventoryResource::collection($inventories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInventoryRequest $request): JsonResponse
    {
        $inventory = Inventory::create($request->validated());

        return response()->json([
            'message' => 'Inventory item created successfully',
            'data' => new InventoryResource($inventory)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventory $inventory): JsonResponse
    {
        return response()->json([
            'data' => new InventoryResource($inventory)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInventoryRequest $request, Inventory $inventory): JsonResponse
    {
        $inventory->update($request->validated());

        return response()->json([
            'message' => 'Inventory item updated successfully',
            'data' => new InventoryResource($inventory)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventory $inventory): JsonResponse
    {
        $inventory->delete();

        return response()->json([
            'message' => 'Inventory item deleted successfully'
        ], 200);
    }

    /**
     * Update inventory quantity (subtract/add)
     */
    public function updateQuantity(Request $request, Inventory $inventory): JsonResponse
    {
        $request->validate([
            'qty' => 'required|numeric',
            'operation' => 'nullable|string|in:add,subtract,set'
        ]);

        $operation = $request->get('operation', 'set');
        $qty = $request->get('qty');

        switch ($operation) {
            case 'add':
                $inventory->qty += $qty;
                break;
            case 'subtract':
                $inventory->qty = max(0, $inventory->qty - $qty);
                break;
            case 'set':
            default:
                $inventory->qty = max(0, $qty);
                break;
        }

        $inventory->save();

        return response()->json([
            'message' => 'Inventory quantity updated successfully',
            'data' => new InventoryResource($inventory)
        ]);
    }
}
