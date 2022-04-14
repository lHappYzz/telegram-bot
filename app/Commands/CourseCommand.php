<?php

namespace App\Commands;

use App\Api\Api;
use App\Bot;

class CourseCommand extends BaseCommand
{
    protected string $description = 'Privat cashless courses.';
    protected string $signature = '/course';

    protected static ?BaseCommand $instance = null;

    public function boot(Bot $bot): void
    {
        $message = '';

        $parameters = [
            'json' => true,
            'exchange' => true,
            'coursid' => '5',
        ];

        $result = Api::get('https://api.privatbank.ua/p24api/pubinfo', $parameters);

        foreach ($result as $block) {
            $message .= '💰BUY: 1' . $block['ccy'] . ' - ' . $block['buy'] . ' ' . $block['base_ccy'].
                "\n💱SALE: 1" . $block['ccy'] . ' - ' . $block['sale'] . ' ' . $block['base_ccy'] . "\n\n";
        }
        $bot->sendMessage($message, $bot->getChat());
    }
}