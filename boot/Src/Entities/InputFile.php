<?php

namespace Boot\Src\Entities;

use Boot\Src\Abstracts\Entity;
use CURLFile;

/**
 * This object represents the contents of a file to be uploaded. Must be posted using multipart/form-data in the
 * usual way that files are uploaded via the browser.
 *
 * @link https://core.telegram.org/bots/api#inputfile
 */
class InputFile extends Entity
{
    /**
     * @param string $filePath
     */
    public function __construct(
        public string $filePath,
    ) {}

    /**
     * Allows to upload a new file on telegram servers using multipart/form-data
     *
     * @return CURLFile
     */
    public function getFile(): CURLFile
    {
        return new CURLFile(realpath($this->filePath));
    }
}