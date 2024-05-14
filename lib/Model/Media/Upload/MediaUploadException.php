<?php
namespace VictorOpusculo\Eadpi\Lib\Model\Media\Upload;

use Exception;

class MediaUploadException extends Exception
{
    public function __construct(string $message, public string $fileName, public int $mediaId)
    {
        parent::__construct($message);
    }
}