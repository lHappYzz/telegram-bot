<?php


namespace App\Api;

use Boot\Traits\Http;

abstract class api {

    use Http;

    public static function run() {
        return self::sendRequest(static::$parameters, static::$url, false);
    }

}