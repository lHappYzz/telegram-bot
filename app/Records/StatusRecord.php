<?php

namespace App\Records;

use App\States\DefaultState;
use App\States\NoState;
use Boot\Database\Record;

class StatusRecord extends Record
{
    public const STATUS_NO_STATUS = null;
    public const STATUS_DEFAULT = 1;

    public static array $statesBindings = [
        self::STATUS_NO_STATUS => NoState::class,
        self::STATUS_DEFAULT => DefaultState::class,
    ];

    protected string $table = 'status';
    protected array $fillable = ['name', 'description'];

    protected int $id;
    protected string $name;
    protected ?string $description = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setID($id): void
    {
        $this->id = $id;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function setDescription($description): void
    {
        $this->description = $description;
    }
}