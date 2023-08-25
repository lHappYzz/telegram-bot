<?php

namespace App\Records;

use Boot\Database\Record;
use Boot\Src\Entities\TelegramChat;

class ChatRecord extends Record
{
    protected string $table = 'chat';

    protected array $fillable = ['id', 'status_id', 'user_id', 'type'];
    protected array $customFields = ['status_id', 'user_id'];

    protected string $boundedTelegramEntity = TelegramChat::class;

    public function getState(): int
    {
        return array_first($this->newQuery()->select(['status_id'])->get());
    }
}