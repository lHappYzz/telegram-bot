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

    public static function GET() {
        return self::sendRequest(static::$parameters, static::$url, false);
    }

    public static function POST() {
        return self::sendRequest(static::$parameters, static::$url, true);
    }

    public static function setApiUrl($newUrl) {
        static::$url = $newUrl;
    }
    public static function setApiParameters($newParameters) {
        static::$parameters = $newParameters;
    }

}