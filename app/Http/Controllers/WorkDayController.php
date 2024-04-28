<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\WorkDay;

class WorkDayController extends Controller
{
    public function isWorkDay(Request $request)
    {
        if (!isset($request->date)) {
            return response('Date is required', 422);
        }

        try {
            $date = Carbon::parse($request->date)->format('Y/m/d');
        } catch (\Exception $e) {
            return response('A valid Date is required', 422);

        }

        $workDay = new WorkDay($date);

        if ($workDay->isWorkday())
            return response()->json(true);

        return response()->json(false);
    }
}
