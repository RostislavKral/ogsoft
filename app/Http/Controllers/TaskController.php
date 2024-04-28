<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;

class TaskController extends Controller
{
    public function approximate(Request $request)
    {
        $start = new \DateTime($request->start);
        $duration = $request->duration;
        $ignoreWorkDays = $request->ignoreWorkDays;
        $startShift = new \DateTime($start->format('Y-m-d').' '.$request->startShift);
        $endShift = new \DateTime($start->format('Y-m-d').' '.$request->endShift);

        $task = new Task($start, $duration, $ignoreWorkDays, $startShift, $endShift);
        $end = $task->approximate();

        return response()->json($end->format('Y-m-d H:i:s'));
    }
}
