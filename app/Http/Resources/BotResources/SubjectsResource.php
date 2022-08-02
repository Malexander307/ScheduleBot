<?php

namespace App\Http\Resources\BotResources;

class SubjectsResource
{
    public static function viewForTelegram($subjects, $date = '', $weekDay = ''){
        if (empty($subjects) || empty($subjects[0]??[])){
            return "{$date}({$weekDay}) :\nweekend";
        }
        $subjects = $subjects[0];
        $message = $date . "($weekDay):\n";
        foreach ($subjects as $subject){
            $message .= $subject['start'] . ' - ' . $subject['end'] . "\n";
            $message .= $subject['room'] . ':' . $subject['name'] . "\n";
            $message .= "\n";
        }
        return $message;
    }
}