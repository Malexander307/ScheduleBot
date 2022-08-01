<?php
namespace App\Traits;

trait BotCommandsTrait{
    public function getUserId():Int
    {
        return request()->message['from']['id'];
    }
}