<?php

namespace App\Records;

use App\States\DefaultState;
use App\States\NoState;
use App\States\PostSuggestionState;
use Boot\Database\Record;
use Boot\Database\Relations\HasManyRelation;

class StatusRecord extends Record
{
    public const null STATUS_NO_STATUS = null;
    public const int STATUS_DEFAULT = 1;
    public const int STATUS_POST_SUGGESTION = 5;

    public static array $statesBindings = [
        self::STATUS_NO_STATUS => NoState::class,
        self::STATUS_DEFAULT => DefaultState::class,
        self::STATUS_POST_SUGGESTION => PostSuggestionState::class,
    ];

    protected string $table = 'status';
    protected array $fillable = ['name', 'description'];

    /**
     * @return HasManyRelation
     */
    public function chats(): HasManyRelation
    {
        return $this->hasMany(ChatRecord::class, 'status_id', 'id');
    }
}