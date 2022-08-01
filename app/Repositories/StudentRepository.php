<?php

namespace App\Repositories;

use App\Models\Student;

class StudentRepository
{
    public function getStudentById(Int $student_id):Student
    {
        return Student::where('student_id', $student_id)->first();
    }
}