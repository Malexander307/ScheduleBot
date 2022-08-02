<?php

namespace App\Helpers;


class DateHelper
{
    public static function getDaysFromStartOfWeekToSomeDay($some_week_day):Int
    {
        $week_days = [
            "пн" => 0, "вт" => 1, "ср" => 2, "чт" => 3, "пт" => 4, "сб" => 5, "нд" => 6,
        ];
        return $week_days[$some_week_day];
    }

    public static function getDaysFromStartOfWeekToSomeDayArray(): array
    {
        return [
            "пн", "вт", "ср", "чт", "пт", "сб", "нд",
        ];
    }
}