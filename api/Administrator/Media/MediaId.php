<?php
namespace VictorOpusculo\Eadpi\Api\Administrator\Media;

use Exception;
use VictorOpusculo\Eadpi\Lib\Helpers\LogEngine;
use VictorOpusculo\Eadpi\Lib\Model\Database\Connection;
use VictorOpusculo\Eadpi\Lib\Model\Media\Media;
use VictorOpusculo\PComp\RouteHandler;

require_once __DIR__ . '/../../../lib/Middlewares/AdminLoginCheck.php';

final class MediaId extends RouteHandler
{
    public function __construct()
    {
        $this->middlewares[] = '\VictorOpusculo\Eadpi\Lib\Middlewares\adminLoginCheck';
    }

    protected $mediaId;

    protected function POST(): void
    {
        $conn = Connection::get();
        try
        {
            if (!Connection::isId($this->mediaId))
                throw new Exception("ID inválido!");

            $media = (new Media([ 'id' => $this->mediaId ]))->getSingle($conn);
            $media->fillPropertiesFromFormInput($_POST, $_FILES);
            $result = $media->save($conn);
            if ($result['affectedRows'] > 0)
            {
                LogEngine::writeLog("Mídia editada! ID: {$media->id->unwrapOr(0)}");
                $this->json([ 'success' => 'Mídia editada com sucesso!' ]);
                exit;
            }
            else
            {
                $this->json([ 'info' => 'Nenhum dado alterado!' ]);
                exit;
            }
        }
        catch (Exception $e)
        {
            LogEngine::writeErrorLog($e->getMessage());
            $this->json([ 'error' => $e->getMessage() ], 500);
            exit;
        }
    }

    protected function DELETE(): void
    {
        $conn = Connection::get();
        try
        {
            if (!Connection::isId($this->mediaId))
                throw new Exception("ID inválido!");

            $media = (new Media([ 'id' => $this->mediaId ]))->getSingle($conn);
            $result = $media->delete($conn);
            if ($result['affectedRows'] > 0)
            {
                LogEngine::writeLog("Mídia excluída! ID: {$media->id->unwrapOr(0)}");
                $this->json([ 'success' => 'Mídia excluída com sucesso!' ]);
                exit;
            }
            else
                throw new Exception("Não foi possível excluir a mídia!");
        }
        catch (Exception $e)
        {
            LogEngine::writeErrorLog($e->getMessage());
            $this->json([ 'error' => $e->getMessage() ], 500);
            exit;
        }
    }
}