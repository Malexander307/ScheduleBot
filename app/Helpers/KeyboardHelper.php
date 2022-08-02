<?php

namespace App\Helpers;

class KeyboardHelper
{
    public static function getKeyboardForDate(){
        return [
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
    }
}