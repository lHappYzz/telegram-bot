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
     * @var FileValidator
     */
    private FileValidator $validator;

    /**
     * @param string $filePath
     */
    public function __construct(
        private string $filePath,
    ) {
        $this->validator = container(FileValidator::class, ['path' => $this->filePath]);
    }

    /**
     * Allows to upload a new photo on telegram servers using multipart/form-data
     *
     * @return CURLFile
     */
    public function getPhoto(): CURLFile
    {
        $this->validator->validatePhoto();

        return new CURLFile(realpath($this->filePath));
    }

    /**
     * Allows to upload a new video on telegram servers using multipart/form-data
     *
     * @return CURLFile
     */
    public function getVideo(): CURLFile
    {
        $this->validator->validateVideo();

        return new CURLFile(realpath($this->filePath));
    }

    /**
     * Allows to upload a new rounded video on telegram servers using multipart/form-data
     *
     * @return CURLFile
     */
    public function getVideoNote(): CURLFile
    {
        $this->validator->validateVideoNote();

        return new CURLFile(realpath($this->filePath));
    }

    /**
     * @return CURLFile
     */
    public function getThumbnail(): CURLFile
    {
        $this->validator->validateThumbnail();

        return new CURLFile(realpath($this->filePath));
    }

    /**
     * @return CURLFile
     */
    public function getAudio(): CURLFile
    {
        $this->validator->validateAudio();

        return new CURLFile(realpath($this->filePath));
    }
}