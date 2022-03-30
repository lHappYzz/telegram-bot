<?php

namespace App\Api;

use Boot\Traits\http;

abstract class api {

    use http;

    public static function get(string $url, ?array $queryParameters = null): array
    {
        return self::request('GET', $url, [
            'query' => $queryParameters
        ]);
    }

    public static function post(string $url, ?array $formData = null): array
    {
        return self::request('POST', $url, [
            'form_params' => $formData
        ]);
    }
}