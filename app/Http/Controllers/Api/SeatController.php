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
        // load all seats and sort naturally (A1, A2, A10 -> A1, A2, A10)
        $seats = Seat::all()->sortBy(function ($s) {
            return $s->code;
        }, SORT_NATURAL | SORT_FLAG_CASE);

        $onlineSeats = Record::where('online', true)->pluck('seat')->toArray();

        $data = $seats->values()->map(function ($s) use ($onlineSeats) {
            return [
                'id' => $s->id,
                'code' => $s->code,
                'online' => in_array($s->code, $onlineSeats),
            ];
        });

        return response()->json(['data' => $data], 200);
    }
}
