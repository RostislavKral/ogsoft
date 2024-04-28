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

        $start = new \DateTime("2024-05-01 07:00:00"); //Task starts before working hours
        $duration = 1100; //minutes

        $startShift = new \DateTime("2024-05-01 08:00:00");
        $endShift = new \DateTime("2024-05-01 16:00:00");   // 8 hours shift

        $ignoreWorkDays = true;
        $task= new Task($start,$duration,$ignoreWorkDays,$startShift,$endShift,$this->workDayFacade);

        $expected = new \DateTime("2024-05-06 10:20:00");
        //dump([$expected->getTimestamp(), $task->approximate()->getTimestamp()]);
        $this->assertEquals($expected->getTimestamp(), $task->approximate()->getTimestamp());
    }
}
