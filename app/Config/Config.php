<?php

namespace App\Config;

use JetBrains\PhpStorm\Pure;

abstract class Config
{
    #[Pure] public static function exists(): bool
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