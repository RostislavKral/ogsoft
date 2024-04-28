<?php
namespace Tests\Unit;


use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;
use App\Task;
use App\WorkDayFacade;
use Illuminate\Support\Carbon;

class TaskTest extends TestCase
{
    private Collection $holidays;
    private WorkDayFacade $workDayFacade;
    public function setUp(): void
    {
        parent::setUp();
        $this->holidays = collect([
            [
                'date' => new Carbon('0000-01-01'),
                'country' => 'cz',
            ],
            [
                'date' => new Carbon('0000-05-01'),
                'country' => 'cz'
            ],
            [
                'date' => new Carbon('0000-05-08'),
                'country' => 'cz'
            ],
            [
                'date' => new Carbon('0000-07-05'),
                'country' => 'cz'
            ],
            [
                'date' => new Carbon('0000-07-06'),
                'country' => 'cz'
            ],
            [
                'date' => new Carbon('0000-09-28'),
                'country' => 'cz'
            ],
            [
                'date' => new Carbon('0000-10-28'),
                'country' => 'cz'
            ],
            [
                'date' => new Carbon('0000-11-17'),
                'country' => 'cz'
            ],
            [
                'date' => new Carbon('0000-12-24'),
                'country' => 'cz'
            ],
            [
                'date' => new Carbon('0000-12-25'),
                'country' => 'cz'
            ],
            [
                'date' => new Carbon('0000-12-26'),
                'country' => 'cz'
            ],
        ]);

        $this->workDayFacade = new WorkDayFacade($this->holidays);
    }
    public function test_ignoring_work_days_may(): void
    {

        $start = new \DateTime("2024-05-01 07:00:00"); //Task starts before working hours so we start our work at 08:00:00
        $duration = 1100; //minutes, 1100 minutes = 18.33 hours

        $startShift = new \DateTime("2024-05-01 08:00:00");
        $endShift = new \DateTime("2024-05-01 16:00:00");   // 8 hours shift

        $ignoreWorkDays = true;
        $task= new Task($start,$duration,$ignoreWorkDays,$startShift,$endShift,$this->workDayFacade);

        /**
         * Because May 1st(Wednesday) is holiday no work will be done this day = 18.33h
         * 2024-05-02 is Thursday and we will work whole shift thus = 10.33h
         * 2024-05-03 is Friday and we will work whole shift = 2.33
         * 2024-05-04 and 05-05 is weekend so no work = 2.33h
         * 2024-05-06 is Monday and the rest of work will be done = 0h and completed
         * Shift started at 08:00:00 so the will be at 10:20:00
         */
        $expected = new \DateTime("2024-05-06 10:20:00");
        //dump([$expected->getTimestamp(), $task->approximate()->getTimestamp()]);
        $this->assertEquals($expected->getTimestamp(), $task->approximate()->getTimestamp());
    }

    public function test_not_ignoring_work_hours_may(): void
    {
        $start = new \DateTime("2024-05-01 07:00:00"); //Task starts before working hours so we start our work at 08:00:00
        $duration = 1100; //minutes, 1100 minutes = 18.33 hours

        $startShift = new \DateTime("2024-05-01 08:00:00");
        $endShift = new \DateTime("2024-05-01 16:00:00");   // 8 hours shift

        $ignoreWorkDays = false;
        $task= new Task($start,$duration,$ignoreWorkDays,$startShift,$endShift,$this->workDayFacade);

        $expected = new \DateTime("2024-05-03 10:20:00");
        //dump([$expected->getTimestamp(), $task->approximate()->getTimestamp()]);
        $this->assertEquals($expected->getTimestamp(), $task->approximate()->getTimestamp());
    }

    public function test_zero_duration(): void
    {
        $start = new \DateTime("2024-05-01 09:00:00");
        $duration = 0; //minutes

        $startShift = new \DateTime("2024-05-01 08:00:00");
        $endShift = new \DateTime("2024-05-01 16:00:00");   // 8 hours shift

        $ignoreWorkDays = false;
        $task= new Task($start,$duration,$ignoreWorkDays,$startShift,$endShift,$this->workDayFacade);

        $expected = new \DateTime("2024-05-01 09:00:00");
        //dump([$expected->getTimestamp(), $task->approximate()->getTimestamp()]);
        $this->assertEquals($expected->getTimestamp(), $task->approximate()->getTimestamp());
    }

    public function test_start_of_task_after_start_shift(): void
    {
        $start = new \DateTime("2024-05-01 09:00:00"); //Task started one hour after the shift start
        $duration = 520; //minutes, 8.67 hours

        $startShift = new \DateTime("2024-05-01 08:00:00");
        $endShift = new \DateTime("2024-05-01 16:00:00");   // 8 hour shift

        $ignoreWorkDays = false;
        $task= new Task($start,$duration,$ignoreWorkDays,$startShift,$endShift,$this->workDayFacade);

        /**
         * Worked on the task 7 hours on May 1st
         * Then worked the rest (1hour 40 minutes) on May 2nd
         */

        $expected = new \DateTime("2024-05-02 09:40:00");
        //dump([$expected->getTimestamp(), $task->approximate()->getTimestamp()]);
        $this->assertEquals($expected->getTimestamp(), $task->approximate()->getTimestamp());
    }

    public function test_start_of_task_after_end_shift(): void
    {
        $start = new \DateTime("2024-05-01 18:00:00"); //Task started one hour after the shift start
        $duration = 60; //minutes, 1 hour

        $startShift = new \DateTime("2024-05-01 08:00:00");
        $endShift = new \DateTime("2024-05-01 16:00:00");   // 8 hour shift

        $ignoreWorkDays = false;
        $task= new Task($start,$duration,$ignoreWorkDays,$startShift,$endShift,$this->workDayFacade);

        /**
         * Worked on the task 0 on May 1st because task started after the working hours
         * Then worked the rest (1hour) on May 2nd
         */

        $expected = new \DateTime("2024-05-02 09:00:00");
        //dump([$expected->getTimestamp(), $task->approximate()->getTimestamp()]);
        $this->assertEquals($expected->getTimestamp(), $task->approximate()->getTimestamp());
    }
}
