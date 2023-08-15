<?php

namespace Boot\Interfaces;

use Boot\Responsibilities;

/**
 * Each telegram Update unit has its own place with a corresponding code base in the application
 * These code bases are responsible for each unit because each of them are handled in different way
 */
interface Responsibility
{
    /**
     * Method describes how and when the responsible code base should run for each of update unit
     *
     * @param Responsibilities $responsibility
     * @return void
     */
    public function responsibilize(Responsibilities $responsibility): void;
}