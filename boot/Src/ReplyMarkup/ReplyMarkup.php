<?php

namespace Boot\Src\ReplyMarkup;

abstract class ReplyMarkup
{
    /**
     * Collect all the necessary, according to the telegram API documentation, class fields into an array
     * @return array
     */
    abstract protected function toArray(): array;
}