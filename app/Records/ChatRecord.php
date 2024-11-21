<?php

namespace App\Records;

use Boot\Database\Record;
use Boot\Database\Relations\BelongsToRelation;
use Boot\Src\Entities\TelegramChat;

class ChatRecord extends Record
{
    protected string $table = 'chat';

    protected array $fillable = ['id', 'status_id', 'user_id', 'type'];
    protected array $customFields = ['status_id', 'user_id'];

    protected string $boundedTelegramEntity = TelegramChat::class;

    /**
     * @return BelongsToRelation
     */
    public function status(): BelongsToRelation
    {
        return $this->belongsTo(StatusRecord::class, 'status_id', 'id');
    }
}