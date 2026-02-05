<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRecordRequest;
use App\Http\Requests\UpdateRecordRequest;
use App\Http\Resources\RecordResource;
use App\Models\Record;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Record::query();

        // Filter by seat
        if ($request->has('seat')) {
            $query->where('seat', $request->seat);
        }

        // Filter by member_ID
        if ($request->has('member_ID')) {
            $query->where('member_ID', 'like', '%' . $request->member_ID . '%');
        }

        // Filter by paid status
        if ($request->has('paid')) {
            $query->where('paid', $request->boolean('paid'));
        }

        // Filter by online status
        if ($request->has('online')) {
            $query->where('online', $request->boolean('online'));
        }

        // Filter by date range
        if ($request->has('date_from')) {
            $query->whereDate('created_date', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->whereDate('created_date', '<=', $request->date_to);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_date');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $records = $query->paginate($perPage);

        return RecordResource::collection($records);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRecordRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['created_date'] = now();
        $data['modified_date'] = now();

        $record = Record::create($data);

        return response()->json([
            'message' => 'Record created successfully',
            'data' => new RecordResource($record)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Record $record): JsonResponse
    {
        return response()->json([
            'data' => new RecordResource($record)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRecordRequest $request, Record $record): JsonResponse
    {
        $data = $request->validated();
        $data['modified_date'] = now();

        $record->update($data);

        return response()->json([
            'message' => 'Record updated successfully',
            'data' => new RecordResource($record)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Record $record): JsonResponse
    {
        $record->delete();

        return response()->json([
            'message' => 'Record deleted successfully'
        ], 200);
    }

    /**
     * Return top members by sum of member_amount.
     */
    public function topMembers(Request $request): JsonResponse
    {
        $limit = (int) $request->get('limit', 10);

        $top = DB::table('records')
            ->select('member_ID', DB::raw('SUM(member_amount) as total_member_amount'))
            ->groupBy('member_ID')
            ->orderByDesc('total_member_amount')
            ->limit($limit)
            ->get();

        return response()->json(['data' => $top]);
    }
}
