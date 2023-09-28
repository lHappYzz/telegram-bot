<?php

namespace Boot\Interfaces;

/**
 * Interface Recordable
 * All TelegramEntities that are going to be saved into DB must implement this interface
 * @package Boot\Interfaces
 */
interface Recordable
{
    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @param array $fillableColumns
     * @return array
     */
    public function getArrayOfAttributes(array $fillableColumns): array;
}
