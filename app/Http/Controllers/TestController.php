<?php

namespace App\Http\Controllers;

use App\Parsers\ScheduleParser;
use App\Repositories\StudentRepository;
use App\Services\StudentService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use MongoDB\Exception\Exception;
use Telegram\Bot\Api;
use Telegram\Bot\Laravel\Facades\Telegram;

class TestController extends Controller
{
    private $telegram;

    public function __construct()
    {
        $this->telegram = new Api('5405790775:AAFsgtRK-nnRgFOHO-qTDAVUCJHkzqxJjYk');
        $command = new \App\BotCommands\StartCommand();
        $this->telegram->addCommand($command);
    }

    public function test(Request $request){
        try {
        $this->telegram->commandsHandler(true);
        $keyboard = [
            "keyboard" => [
                [
                    [
                        "text" => "пн",
                    ],
                    [
                        "text" => "вт",
                    ],
                    [
                        "text" => "ср",
                    ],
                    [
                        "text" => "чт",
                    ],
                    [
                        "text" => "пт",
                    ],
                    [
                        "text" => "сб",
                    ],
                    [
                        "text" => "нд",
                    ],
                ],
                [
                    [
                        "text" => "Минулий тиждень"
                    ],
                    [
                        "text" => 'Сьогодні'
                    ],
                    [
                        "text" => 'Наступний тиждень'
                    ]
                ]
            ],
            "resize_keyboard" => true,
        ];
        $user = (new StudentRepository())->getStudentById($request->message['from']['id']);
        $weekStartDate = (new StudentService())->changeCurrentWeek($request->message['from']['id'], $request->message['text']);
        $user->save();
        $this->telegram->sendMessage(
            [
                "chat_id" => $request->message['chat']['id'],
                "text" => $weekStartDate,
                "reply_markup" => json_encode($keyboard)
            ]);
        }catch (\ErrorException $exception){
            logs()->info($exception);
        }
    }
}
