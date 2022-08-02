<?php

namespace App\Http\Controllers;

use App\Helpers\KeyboardHelper;
use App\Helpers\MessageHelper;
use App\Http\Resources\BotResources\SubjectsResource;
use App\Models\Student;
use App\Parsers\ScheduleParser;
use App\Repositories\StudentRepository;
use App\Services\StudentService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Telegram\Bot\Api;

class TestController extends Controller
{
    private $telegram;
    private $user;

    public function __construct()
    {
        $this->telegram = new Api('5405790775:AAFsgtRK-nnRgFOHO-qTDAVUCJHkzqxJjYk');
        $command = new \App\BotCommands\StartCommand();
        $this->telegram->addCommand($command);
        $user = (new StudentRepository())->getStudentById(request()->message['from']['id']);
    }

    public function test(Request $request){
        try {
            $this->telegram->commandsHandler(true);
            $user = (new StudentRepository())->getStudentById(request()->message['from']['id']);
            $weekStartDate = (new StudentService())->changeCurrentWeek($request->message['from']['id'], $request->message['text']);
            $user = MessageHelper::checkGroup($user, $request->message['text']);
            if ($user->group != null) {
                $weekStartDate = Carbon::parse($weekStartDate)->format('d.m.Y');
                $weekDay = Carbon::parse($weekStartDate)->format('l');
                $message = SubjectsResource::viewForTelegram(
                    (new ScheduleParser($weekStartDate, $user->group))->parse(),
                    $weekStartDate,
                    __('date.' . $weekDay)
                );
                $this->telegram->sendMessage([
                                                 "chat_id" => $request->message['chat']['id'],
                                                 "text" => $message,
                                                 "reply_markup" => json_encode(KeyboardHelper::getKeyboardForDate())
                                             ]);
            }
        }catch (\ErrorException $exception){
            logs()->info($exception);
        }
    }
}
