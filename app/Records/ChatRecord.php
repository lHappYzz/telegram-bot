<?php

namespace App\Records;

use Boot\Database\Record;
use Boot\Src\Entities\TelegramChat;
use Boot\Traits\Helpers;

class ChatRecord extends Record
{
    use Helpers;

    protected string $table = 'chat';

    protected array $fillable = ['id', 'status_id', 'user_id', 'type'];
    protected array $customFields = ['status_id', 'user_id'];

    protected string $boundedTelegramEntity = TelegramChat::class;

    public function getState(): int
    {
        return $this->arrayFirst($this->newQuery()->select(['status_id'])->get());
    }
}