<?php
namespace VictorOpusculo\Eadpi\Api\Administrator\Certificates;

use Exception;
use VictorOpusculo\Eadpi\Lib\Helpers\LogEngine;
use VictorOpusculo\Eadpi\Lib\Model\Database\Connection;
use VictorOpusculo\Eadpi\Lib\Model\Settings\DefaultCertificateBgMediaId;
use VictorOpusculo\PComp\RouteHandler;

require_once __DIR__ . '/../../../lib/Middlewares/AdminLoginCheck.php';
require_once __DIR__ . '/../../../lib/Middlewares/JsonBodyParser.php';

final class SetBgImage extends RouteHandler
{
    public function __construct()
    {
        $this->middlewares[] = '\VictorOpusculo\Eadpi\Lib\Middlewares\adminLoginCheck';
        $this->middlewares[] = '\VictorOpusculo\Eadpi\Lib\Middlewares\jsonParser';
    }

    protected function PUT(): void
    {
        $conn = Connection::get();
        try
        {
            $newId = $_POST['data']['newId'] ?? null;
            $sett = (new DefaultCertificateBgMediaId([ 'value' => $newId ]));

            $result = $sett->save($conn);
            if ($result['affectedRows'] > 0)
            {
                LogEngine::writeLog("Fundo padrão de certificado alterado! Para ID: $newId");
                $this->json([ 'success' => 'Fundo de certificado alterado com sucesso!' ]);
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
}