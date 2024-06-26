<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Class WorkDayFacade
 *
 * Providing a facade for determining whether the day is work day or not
 *
 * @method bool isWorkDay()
 * @method bool isHoliday()
 * @method bool isEastern()
 * @method bool isWeekend()
 */
class WorkDayFacade
{
    const SUNDAY = 0;
    const SATURDAY = 6;
    private static Carbon $date;
    private static Collection $holidays;
    public function __construct($holidays)
    {
        self::$holidays = $holidays;
    }

    /**
     * Wrapper method determining if the date is a workday or not using rules
     *
     * @return bool
     */
    public static function isWorkDay($date): bool
    {
        self::$date = Carbon::create($date);

        if (self::isWeekend())
            return false;
        else if (self::isEaster())
            return false;
        else if (self::isHoliday())
            return false;

        return true;
    }

    /**
     * Rule method for weekend
     *
     * @return bool
     */
    private static function isWeekend(): bool
    {

        if (self::$date->dayOfWeek() == self::SATURDAY || self::$date->dayOfWeek() == self::SUNDAY)
            return true; // 6 = Saturday, 0 = Sunday

        return false;
    }

    /**
     * Rule method for holidays passed in constructor
     *
     * @return bool
     */
    private static function isHoliday(): bool
    {
        foreach (self::$holidays as $holiday) {
            //dump($holiday['date']);
            if (Carbon::create($holiday['date'])->format('m-d') == self::$date->format('m-d'))
                return true; //Compare day and month without a year
        }

        return false;
    }

    /**
     * Rule method for Easter
     *
     * @return bool
     */
    private static function isEaster(): bool
    {
        //based on: https://cs.wikipedia.org/wiki/V%C3%BDpo%C4%8Det_data_Velikonoc
        $year = self::$date->format('Y');

        //for 2000-2099 years constants are m = 24 and n = 5
        $m = 24;
        $n = 5;

        $a = $year % 19;
        $b = $year % 4;
        $c = $year % 7;
        $d = (19 * $a + $m) % 30;
        $e = ($n + 2 * $b + 4 * $c + 6 * $d) % 7;

        $numberOfDaysToAdd = $d + $e;
        $friday = $numberOfDaysToAdd - 2;
        $monday = $numberOfDaysToAdd + 1;

        $baseDate = Carbon::create($year . '-03-22');
        $friday = Carbon::create($baseDate)->addDays($friday);
        $monday = Carbon::create($baseDate)->addDays($monday);

        if ($friday == self::$date || $monday == self::$date)
            return true;

        return false;
    }


}
