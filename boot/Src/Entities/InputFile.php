<?php

namespace Boot\Src\Entities;

use Boot\Classes\FileValidator;
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
        private string $filePath,
    ) {}

    /**
     * Allows to upload a new photo on telegram servers using multipart/form-data
     *
     * @return CURLFile
     */
    public function getPhoto(): CURLFile
    {
        container(FileValidator::class)->validatePhoto($this->filePath);

        return new CURLFile(realpath($this->filePath));
    }

    /**
     * Allows to upload a new video on telegram servers using multipart/form-data
     *
     * @return CURLFile
     */
    public function getVideo(): CURLFile
    {
        container(FileValidator::class)->validateVideo($this->filePath);

        return new CURLFile(realpath($this->filePath));
    }

    /**
     * @return CURLFile
     */
    public function getThumbnail(): CURLFile
    {
        container(FileValidator::class)->validateThumbnail($this->filePath);

        return new CURLFile(realpath($this->filePath));
    }

    /**
     * @return CURLFile
     */
    public function getAudio(): CURLFile
    {
        container(FileValidator::class)->validateAudio($this->filePath);

        return new CURLFile(realpath($this->filePath));
    }
}