<?php

namespace Boot\Interfaces;

/**
 * Interface Recordable
 * All TelegramEntities that are going to be saved into DB must implement this interface
 * @package Boot\Interfaces
 */
interface Recordable
{
    public function getId(): int;

    public function getArrayOfAttributes(array $fillableColumns): array;
}
