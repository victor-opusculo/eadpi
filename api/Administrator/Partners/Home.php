<?php
namespace VictorOpusculo\Eadpi\Api\Administrator\Partners;

use Exception;
use VictorOpusculo\Eadpi\Lib\Helpers\LogEngine;
use VictorOpusculo\Eadpi\Lib\Model\Database\Connection;
use VictorOpusculo\Eadpi\Lib\Model\Partners\Partner;
use VictorOpusculo\PComp\RouteHandler;

require_once __DIR__ . '/../../../lib/Middlewares/AdminLoginCheck.php';
require_once __DIR__ . '/../../../lib/Middlewares/JsonBodyParser.php';

final class Home extends RouteHandler
{
    public function __construct()
    {
        $this->middlewares[] = '\VictorOpusculo\Eadpi\Lib\Middlewares\adminLoginCheck';
        $this->middlewares[] = '\VictorOpusculo\Eadpi\Lib\Middlewares\jsonParser';
    }

    protected function POST(): void
    {
        $conn = Connection::get();
        try
        {
            $partner = new Partner();
            $partner->fillPropertiesFromFormInput($_POST['data'] ?? '');

            if (!$partner->end->unwrapOr(false))
                $partner->end = null;

            $result = $partner->save($conn);
            if ($result['newId'])
            {
                LogEngine::writeLog("Parceiro criado! ID: $result[newId]");
                $this->json([ 'success' => 'Parceiro criado com sucesso!', 'data' => [ 'newId' => $result['newId'] ] ]);
            }
            else
                throw new Exception("Não foi possível criar o parceiro!");
        }
        catch (Exception $e)
        {
            LogEngine::writeErrorLog($e->getMessage());
            $this->json([ 'error' => $e->getMessage() ], 500);
            exit;
        }
    }
}