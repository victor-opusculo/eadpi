<?php
namespace VictorOpusculo\Eadpi\Api\Administrator;

use Exception;
use VictorOpusculo\Eadpi\Lib\Helpers\LogEngine;
use VictorOpusculo\Eadpi\Lib\Model\Database\Connection;
use VictorOpusculo\Eadpi\Lib\Model\Administrators\Administrator;
use VictorOpusculo\PComp\RouteHandler;

require_once __DIR__ . '/../../lib/Middlewares/JsonBodyParser.php';
require_once __DIR__ . '/../../lib/Middlewares/AdminLoginCheck.php';

class AdminId extends RouteHandler
{
    public function __construct()
    {
        $this->middlewares[] = '\VictorOpusculo\Eadpi\Lib\Middlewares\jsonParser';
        $this->middlewares[] = '\VictorOpusculo\Eadpi\Lib\Middlewares\adminLoginCheck';
    }

    protected $id;

    protected function PUT() : void
    {
        $conn = Connection::get();
        try
        {
            if (!isset($this->id) || !Connection::isId($this->id))
                throw new Exception("ID inválido.");

            $admin = (new Administrator([ 'id' => $this->id ]))->getSingle($conn);
            $admin
            ->fillPropertiesFromFormInput($_POST['data'] ?? []);

            if ($admin->existsAnotherAdminWithEmail($conn))
                throw new Exception("O e-mail informado já está em uso por outro administrador.");

            if (!empty($_POST['data']['administrators:password']))
            {
                if (!$admin->checkPassword($_POST['data']['administrators:currpassword'] ?? ''))
                    throw new Exception("Senha atual incorreta!");

                $admin->hashPassword($_POST['data']['administrators:password']);
            }

            $result = $admin->save($conn);
            if ($result['affectedRows'] > 0)
            {
                LogEngine::writeLog("Dados de administrador atualizados pelo próprio.");
                $this->json([ 'success' => 'Dados atualizados com sucesso!' ]);
            }
            else
            {
                LogEngine::writeLog("Dados de administrador não atualizados. Ação executada pelo próprio admin.");
                $this->json([ 'info' => 'Nenhum dado alterado.' ]);
            }
        }
        catch (Exception $e)
        {
            $this->json([ 'error' => $e->getMessage() ], 500);
            exit;
        }
    }
}