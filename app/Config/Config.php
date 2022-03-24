<?php

namespace App\Config;

abstract class Config
{
    public static function exists(): bool
    {
        return file_exists('app/app.ini');
    }

    public static function database(): array
    {
        return parse_ini_file('app/app.ini', true)['database'];
    }

    public static function bot(): array
    {
        return parse_ini_file('app/app.ini', true)['bot'];
    }

    public static function timezone(): string
    {
        return parse_ini_file('app/app.ini')['timezone'];
    }
}