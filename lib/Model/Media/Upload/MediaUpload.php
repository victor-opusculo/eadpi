<?php

namespace VictorOpusculo\Eadpi\Lib\Model\Media\Upload;

use VictorOpusculo\Eadpi\Lib\Model\FileUploadUtils;

final class MediaUpload
{
    private function __construct() { }

    public const UPLOAD_DIR = 'uploads/media/';
    public const ALLOWED_TYPES = null;
    public const MAX_SIZE = 10485760 /* 10MB */;

    /**
     * Processa o upload de arquivo de mídia.
     * @param int $mediaId ID da mídia
     * @param array $filePostData Array $_FILES
     * @param string $fileInputElementName Nome do elemento do tipo file do formulário de upload
     * @return bool
     * @throws MediaUploadException
     * */
    public static function uploadMediaFile(int $mediaId, array $filePostData, string $fileInputElementName) : bool
    {
            $fullDir = __DIR__ . "/../../../../" . self::UPLOAD_DIR;
            $fileName = basename($filePostData[$fileInputElementName]["name"]);
            $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            $uploadFile = $fullDir . "$mediaId.$fileExtension";
        
            FileUploadUtils::checkForUploadError($filePostData[$fileInputElementName], self::MAX_SIZE, [self::class, 'throwException'], [ $fileName, $mediaId ], self::ALLOWED_TYPES);

            if (!is_dir($fullDir))
                mkdir($fullDir, 0777, true);
                    
            if (!file_exists($uploadFile))
            {
                if (move_uploaded_file($filePostData[$fileInputElementName]["tmp_name"], $uploadFile))
                    return true;
                else
                    self::throwException("Erro ao mover o arquivo após upload.", $fileName, $mediaId);
            }
            else
                self::throwException("Arquivo enviado já existente no servidor.", $fileName, $mediaId);
            
            return false;		
    }

    public static function getExtension(array $filePostData, string $fileInputElementName ) : string
    {
        $fileName = basename($filePostData[$fileInputElementName]["name"]);
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        return $fileExtension;
    }

    public static function checkForUploadError(array $filePostData, string $fileInputElementName) : void
    {
        $fileName = basename($filePostData[$fileInputElementName]["name"]);
        FileUploadUtils::checkForUploadError($filePostData[$fileInputElementName], self::MAX_SIZE, [self::class, 'throwException'], [ $fileName, -1 ], self::ALLOWED_TYPES);
    }

    public static function deleteMediaFile(int $mediaId) : bool
    {
        $locationFilePath = __DIR__ . "/../../../../" . self::UPLOAD_DIR .  "$mediaId.*";
        
        $files = glob($locationFilePath);
        foreach ($files as $file)
            if (file_exists($file))
            {
                if(unlink($file))
                    return true;
                else
                    self::throwException("Erro ao excluir o arquivo de artigo.", basename($file), $mediaId);
            }

        return false;
    }

    /*
    public static function cleanArticleFolder(int $articleId)
    {
        $fullDir = str_replace("{articleId}", (string)$articleId, __DIR__ . "/../../../../" . self::UPLOAD_DIR);
        
        if (is_dir($fullDir))
        {
            $files = glob($fullDir . "*"); // get all file names
            
            foreach($files as $file)
            {
                if(is_file($file)) 
                    unlink($file); // delete file
            }
        }
    }

    public static function checkForEmptyArticleDir(int $articleId)
    {        
        if (is_dir($fullDir))
            if (FileUploadUtils::isDirEmpty($fullDir))
                rmdir($fullDir);
    }
*/
    public static function throwException(string $message, string $fileName, int $mediaId)
    {
        throw new MediaUploadException($message, $fileName, $mediaId); 
    }
}
