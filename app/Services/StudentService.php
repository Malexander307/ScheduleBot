<?php

namespace App\Services;

use App\Helpers\DateHelper;
use App\Models\Student;
use App\Repositories\StudentRepository;
use Illuminate\Support\Carbon;

class StudentService
{
    public function create(Int $student_id):Student
    {
        $now = Carbon::now();
        $weekStartDate = $now->startOfWeek()->format('Y-m-d');
        return Student::create([
            "student_id" => $student_id,
            "week_start" => $weekStartDate,
            "group" => null
        ]);
    }

    public function updateWeekStart(String $new_week_start, Int $student_id):Student
    {
        $user = (new StudentRepository())->getStudentById($student_id);
        $user->week_start = $new_week_start;
        $user->save();
        return $user;
    }

    public function changeCurrentWeek($student_id, $message_text):String
    {
    try {
        $student = (new StudentRepository())->getStudentById($student_id);
        if($message_text == 'Минулий тиждень'){
            $weekStartDate = Carbon::parse($student->week_start)->subWeek()->format('Y-m-d');
            $student->week_start = $weekStartDate;
        }elseif($message_text == 'Наступний тиждень') {
            $weekStartDate = Carbon::parse($student->week_start)->addWeek()->format('Y-m-d');
            $student->week_start = $weekStartDate;
        }elseif ($message_text == 'Сьогодні'){
            $weekStartDate = Carbon::now()->format('Y-m-d');
        }elseif (in_array($message_text, DateHelper::getDaysFromStartOfWeekToSomeDayArray())){
            $weekStartDate = Carbon::parse($student->week_start)
                ->addDays(DateHelper::getDaysFromStartOfWeekToSomeDay($message_text))->format('Y-m-d');
        }else{
            $weekStartDate = $student->week_start;
        }
        $student->save();
        return $weekStartDate;
    }catch (\ErrorException $exception){
        logs()->info($exception);
    }
    }
}