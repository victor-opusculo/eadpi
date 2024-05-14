<?php
namespace VictorOpusculo\Eadpi\Api\Administrator\Partners;

use Exception;
use VictorOpusculo\Eadpi\Lib\Helpers\LogEngine;
use VictorOpusculo\Eadpi\Lib\Model\Database\Connection;
use VictorOpusculo\Eadpi\Lib\Model\Partners\Partner;
use VictorOpusculo\PComp\RouteHandler;

require_once __DIR__ . '/../../../lib/Middlewares/AdminLoginCheck.php';
require_once __DIR__ . '/../../../lib/Middlewares/JsonBodyParser.php';

final class PartnerId extends RouteHandler
{
    public function __construct()
    {
        $this->middlewares[] = '\VictorOpusculo\Eadpi\Lib\Middlewares\adminLoginCheck';
        $this->middlewares[] = '\VictorOpusculo\Eadpi\Lib\Middlewares\jsonParser';
    }

    protected $partnerId;

    protected function PUT(): void
    {
        $conn = Connection::get();
        try
        {
            if (!Connection::isId($this->partnerId))
                throw new Exception("ID inválido!");

            $partner = (new Partner([ 'id' => $this->partnerId ]))->getSingle($conn);
            $partner->fillPropertiesFromFormInput($_POST['data'] ?? '');

            if (!$partner->end->unwrapOr(false))
                $partner->end = null;

            $result = $partner->save($conn);
            if ($result['affectedRows'] > 0)
            {
                LogEngine::writeLog("Parceiro editado! ID: {$partner->id->unwrapOr(0)}");
                $this->json([ 'success' => 'Parceiro editado com sucesso!' ]);
            }
            else
                $this->json([ 'info' => 'Nenhum dado alterado.' ]);
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
            if (!Connection::isId($this->partnerId))
                throw new Exception("ID inválido!");

            $partner = (new Partner([ 'id' => $this->partnerId ]))->getSingle($conn);
            $result = $partner->delete($conn);
            if ($result['affectedRows'] > 0)
            {
                LogEngine::writeLog("Parceiro excluído! ID: {$partner->id->unwrapOr(0)}");
                $this->json([ 'success' => "Parceiro excluído com sucesso!" ]);
            }
            else
                throw new Exception("Não foi possível excluir o parceiro!");
        }
        catch (Exception $e)
        {
            LogEngine::writeErrorLog($e->getMessage());
            $this->json([ 'error' => $e->getMessage() ], 500);
            exit;
        }
    }
}