<?php

namespace App;
use Carbon\Carbon;

class WorkDay {
    private $date;
    private $holidays;
    public function __construct($date, $holidays) {
    $this->date = Carbon::create($date);
    $this->holidays = $holidays;
    }

    public function isWorkDay(): bool
    {
        if ($this->isWeekend())
            return false;
        else if ($this->isEaster())
            return false;
        else if ($this->isHoliday())
            return false;

        return true;
    }

    private function isWeekend()
    {

       if($this->date->dayOfWeek() == 6 || $this->date->dayOfWeek() == 0) return true; // 6 = Saturday, 0 = Sunday

       return false;
    }

    private function isHoliday(): bool
    {
        foreach($this->holidays as $holiday)
        {
            if(Carbon::create($holiday->date)->format('m-d') == $this->date->format('m-d')) return true; //Compare day and month without a year
        }

        return false;
    }
    private function isEaster()
    {
        //based on: https://cs.wikipedia.org/wiki/V%C3%BDpo%C4%8Det_data_Velikonoc
        $year = $this->date->format('Y');

        //for 2000-2099 years constants m = 24 and n = 5
        $m = 24;
        $n = 5;

        $a = $year % 19;
        $b = $year % 4;
        $c = $year % 7;
        $d = (19*$a + $m) % 30;
        $e = ($n + 2*$b + 4*$c + 6*$d) % 7;

        $numberOfDaysToAdd = $d + $e;
        $friday = $numberOfDaysToAdd - 2;
        $monday = $numberOfDaysToAdd + 1;

        $baseDate = Carbon::create($year.'-03-22');
        $friday = Carbon::create($baseDate)->addDays($friday);
        $monday = Carbon::create($baseDate)->addDays($monday);

        if($friday == $this->date || $monday == $this->date) return true;

        return false;
    }


}
