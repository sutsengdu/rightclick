<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Seat;
use App\Models\Record;
use Illuminate\Http\Request;

class SeatController extends Controller
{
    /**
     * Return list of seats with online status (true if a record with online=1 exists for the seat)
     */
    public function index(Request $request)
{
    // Get latest record per seat
    $latestRecords = Record::select('seat', 'online', 'created_date')
        ->orderBy('created_date', 'desc')
        ->get()
        ->groupBy('seat')
        ->map(fn ($g) => $g->first());

    $seats = Seat::all()->sortBy(fn ($s) => $s->code, SORT_NATURAL | SORT_FLAG_CASE);

    $data = $seats->values()->map(function ($s) use ($latestRecords) {
        $record = $latestRecords->get($s->code);

        return [
            'id' => $s->id,
            'code' => $s->code,
            'online' => $record?->online ?? false,
            'online_at' => $record?->created_date?->format('Y-m-d H:i:s'),
        ];
    });

    return response()->json(['data' => $data], 200);
}

}
