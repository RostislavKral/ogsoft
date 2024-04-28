<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\WorkDayFacade;
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
        $holidays = Holiday::where('country', 'cz')->get();

        $workDay = new WorkDayFacade($holidays);

        if ($workDay::isWorkday($date))
            return response()->json(true);

        return response()->json(false);
    }
}
