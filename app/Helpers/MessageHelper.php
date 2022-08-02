<?php

namespace App\Helpers;

use App\Models\Student;

class MessageHelper
{
    public static function checkGroup(Student $user, String $message){
        if($user->group === null && !str_contains($message, "/") && !in_array($message, DateHelper::getDaysFromStartOfWeekToSomeDayArray())){
            $user->group = $message;
            $user->save();
        }
        return $user;
    }
}