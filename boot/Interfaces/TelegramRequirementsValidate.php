<?php

namespace Boot\Interfaces;

use Exception;

interface TelegramRequirementsValidate
{
    /**
     * Validate fields to be sent according to telegram requirements.
     *
     * @return void
     * @throws Exception
     */
    public function validate(): void;
}