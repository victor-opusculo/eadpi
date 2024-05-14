<?php

namespace VictorOpusculo\Eadpi\Lib\Model;

final class FileUploadUtils
{
    private function __construct() { }

    /**
     * Verifica se um determinado diretório está vazio. Recomenda-se usar o parâmetro com a constante __DIR__.
     * @param string $dir Diretório a ser verificado
     * @return bool 'true' para vazio, 'false' para não vazio.
     * */ 
    public static function isDirEmpty(string $dir) : bool
    {
        $handle = opendir($dir);
        while (false !== ($entry = readdir($handle))) 
        {
            if ($entry != "." && $entry != "..") 
            {
                closedir($handle);
                return false;
            }
        }
        closedir($handle);
        return true;
    }

    /**
     * Verifica se houve erros após o upload inspecionando o arquivo no array $_FILES.
     * @param array $fileData Entrada do array $_FILES.
     * @param float $maxSize Tamanho máximo do arquivo em bytes.
     * @param callable $throwExceptionFunction Função callback que disparará exceção quando necessário. Formato (string $messageError, ...) : void
     * @param array $throwExceptionArguments Parâmetros da função callback após o primeiro (mensagem).
     * @param array $allowedMimeTypes Conjunto de tipos MIME aceitos. Se null, qualquer tipo de arquivo é aceito.
     * @return void
     * */
    public static function checkForUploadError(array $fileData, float $maxSize, callable $throwExceptionFunction, array $throwExceptionArguments = [], $allowedMimeTypes = null)
    {
        switch ($fileData['error'])
        {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $throwExceptionFunction("Erro no upload: Tamanho máximo de arquivo excedido!", ...$throwExceptionArguments); break;
            case UPLOAD_ERR_PARTIAL:
                $throwExceptionFunction("Erro no upload: Arquivo com upload parcialmente feito!", ...$throwExceptionArguments); break;
            case UPLOAD_ERR_NO_FILE:
                $throwExceptionFunction("Erro no upload: Nenhum arquivo definido para o upload!", ...$throwExceptionArguments); break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $throwExceptionFunction("Erro no upload: Diretório temporário de upload ausente!", ...$throwExceptionArguments); break;
            case UPLOAD_ERR_CANT_WRITE:
                $throwExceptionFunction("Erro no upload: Arquivo de upload não pôde ser gravado em disco!", ...$throwExceptionArguments); break;
            case UPLOAD_ERR_OK: 
            default:
                break;
        }

        if ($fileData['size'] > $maxSize)
            $throwExceptionFunction("Erro no upload: Arquivo acima do tamanho permitido!", ...$throwExceptionArguments);

        if (isset($allowedMimeTypes))
            if (array_search($fileData['type'], $allowedMimeTypes) === false)
                $throwExceptionFunction("Erro no upload: Arquivo em formato não suportado!", ...$throwExceptionArguments);
    }
}