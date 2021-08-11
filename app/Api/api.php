<?php


namespace App\Api;

use Boot\Traits\http;

abstract class api {

    use http;

    public static function run($url = null) {
        if ($url) {
            return self::sendRequest(static::$parameters, $url, false);
        }
        return self::sendRequest(static::$parameters, static::$url, false);
    }

}