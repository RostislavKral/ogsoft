<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;
use App\Task;
use Illuminate\Support\Facades\Validator;
use App\WorkDayFacade;

class TaskController extends Controller
{
    public function approximate(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'start' => 'required|date',
                'duration' => 'required|integer|min:0',
                'ignoreWorkDays' => 'required|boolean',
                'startShift' => 'required|date_format:H:i:s',
                'endShift' => 'required|date_format:H:i:s',
            ]
        );

        if ($validator->fails())
            return response()->json($validator->errors(), 422);
        $validated = $validator->validate();

        $start = new \DateTime($validated['start']);
        $duration = $validated['duration'];
        $ignoreWorkDays = $validated['ignoreWorkDays'];
        $startShift = new \DateTime($start->format('Y-m-d') . ' ' . $validated['startShift']);
        $endShift = new \DateTime($start->format('Y-m-d') . ' ' . $validated['endShift']);

        if($startShift >= $endShift)
            return response()->json("Start of a shift has to be before the End of shift");

        $holidays = Holiday::where('country', 'cz')->get();
        $workDayFacade = new WorkDayFacade($holidays);

        $task = new Task($start, $duration, $ignoreWorkDays, $startShift, $endShift, $workDayFacade);

        $end = $task->approximate();

        return response()->json($end->format('Y-m-d H:i:s'));
    }
}
