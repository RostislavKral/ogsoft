<?php

namespace App;

use Illuminate\Support\Collection;
use App\WorkDayFacade;

/**
 * Class Task
 *
 * @method \DateTime approximate()
 */
class Task
{
    private \DateTime $start, $startShift, $endShift;
    private int $duration;

    private bool $ignoreWorkDays;

    private Collection $holidays;
    private WorkDayFacade $workDayFacade;

    public function __construct($start, $duration, $ignoreWorkDays, $startShift, $endShift, $workDayFacade)
    {
        $this->start = $start;
        $this->duration = $duration * 60; // to seconds
        $this->ignoreWorkDays = $ignoreWorkDays;
        $this->startShift = $startShift;
        $this->endShift = $endShift;
        $this->workDayFacade = $workDayFacade;
    }

    /**
     * Method does calculate number of (work) days needed to accomplish the task and returns the date and time when task will be finished.
     *
     * @return \DateTime returns the approximate date and time of the task
     */
    public function approximate(): \DateTime
    {
        //dd($this->holidays);
        $offset = 0;
        $firstDay = true;

        if ($this->start <= $this->startShift) //Begining of the Task is before start of the shift
            $this->start = $this->startShift;   // So skip non-working hours
        else
            $offset = $this->start->getTimestamp() - $this->startShift->getTimestamp(); /* If start of the Task some time after the
        shift start, calculate the offset */

        $result = clone $this->start;

        $lengthOfTheShift = $this->endShift->getTimestamp() - $this->startShift->getTimestamp();

        if($this->start >= $this->endShift){    //Task starts after the working hours
            $firstDay = false;
            $offset -= $lengthOfTheShift;
            $result = clone $this->startShift;
            $result->modify('+1day');
        }


        while ($this->duration > 0) {

            if ($this->ignoreWorkDays) {
                if (!$this->workDayFacade::isWorkDay($this->startShift)) {
                    $firstDay = false;
                    $this->startShift->modify('+1day');
                    $result = clone $this->startShift;
                    continue;
                }
            }

            $this->duration -= $lengthOfTheShift;

            if ($firstDay)
                $this->duration += $offset;

            if ($this->duration < 0) {
                $rest = abs($this->duration);

                $result->add(new \DateInterval('PT' . ($lengthOfTheShift - $rest) . 'S'));
                return $result;
            }


            $this->startShift->modify('+1day');
            $result = clone $this->startShift;

            $firstDay = false;
        }


        return $result;
    }

}
