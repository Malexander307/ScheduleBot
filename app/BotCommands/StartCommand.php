<?php

namespace App\BotCommands;

use App\Repositories\StudentRepository;
use App\Services\StudentService;
use App\Traits\BotCommandsTrait;
use Telegram\Bot\Commands\Command;

class StartCommand extends Command
{
    use BotCommandsTrait;

    /**
     * @var string Command Name
     */
    protected $name = "start";

    /**
     * @var string Command Description
     */
    protected $description = "Start Command to get you started";

    /**
     * @inheritdoc
     */
    public function handle():void
    {
        try {
            $user = (new StudentRepository())->getStudentById($this->getUserId());
            if (!isset($user)) {
                (new StudentService())->create($this->getUserId());
            }
            $this->replyWithMessage(['text' => 'Введіть вашу групу']);
        }catch (\ErrorException $exception){
            logs()->info($exception);
        }
    }
}