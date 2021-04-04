<?php


namespace App\Commands;

use App\Api\privatApi;
use App\Bot;

class courseCommand extends baseCommand {

    protected $description = 'Privat cashless courses.';
    protected $signature = '/course';

    protected static $instance = null;

    public function boot(Bot $bot) {
        $message = '';
        $result = privatApi::run();
        foreach ($result as $block) {
            $message .= '💰BUY: 1' . $block['ccy'] . ' - ' . $block['buy'] . ' ' . $block['base_ccy'].
                "\n💱SALE: 1" . $block['ccy'] . ' - ' . $block['sale'] . ' ' . $block['base_ccy'] . "\n\n";
        }
        $bot->sendMessage($message);
    }
}