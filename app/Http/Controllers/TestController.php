<?php

namespace App\Http\Controllers;

use App\Parsers\ScheduleParser;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test(){
        (new ScheduleParser())->parse();
    }
}
