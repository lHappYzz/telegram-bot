<?php

namespace App\Commands;

use App\Api\api;
use App\bot;

class courseCommand extends baseCommand {

    protected string $description = 'Privat cashless courses.';
    protected string $signature = '/course';

    protected static ?baseCommand $instance = null;

    public function boot(bot $bot): void
    {
        $message = '';

        $parameters = [
            'json' => true,
            'exchange' => true,
            'coursid' => '5',
        ];

        $result = api::get('https://api.privatbank.ua/p24api/pubinfo', $parameters);

        foreach ($result as $block) {
            $message .= '💰BUY: 1' . $block['ccy'] . ' - ' . $block['buy'] . ' ' . $block['base_ccy'].
                "\n💱SALE: 1" . $block['ccy'] . ' - ' . $block['sale'] . ' ' . $block['base_ccy'] . "\n\n";
        }
        $bot->sendMessage($message, $bot->getChat());
    }
}