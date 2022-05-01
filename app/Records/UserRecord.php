<?php

namespace App\Records;

use Boot\Database\Record;
use Boot\Src\TelegramUser;

class UserRecord extends Record
{
    protected string $table = 'user';
    protected array $fillable = ['id', 'first_name', 'last_name', 'user_name', 'language_code', 'is_bot'];

    protected string $boundedTelegramEntity = TelegramUser::class;
}