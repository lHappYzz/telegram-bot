<?php


namespace App\Api;


abstract class privatApi extends api {

    protected static string $url = 'https://api.privatbank.ua/p24api/pubinfo';
    protected static array $parameters = [
        'json' => true,
        'exchange' => true,
        'coursid' => '5',
    ];
}