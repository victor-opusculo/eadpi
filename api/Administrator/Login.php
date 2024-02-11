<?php

namespace VictorOpusculo\Eadpi\Api\Administrator;

use Exception;
use VictorOpusculo\Eadpi\Lib\Helpers\LogEngine;
use VictorOpusculo\Eadpi\Lib\Helpers\UserTypes;
use VictorOpusculo\Eadpi\Lib\Model\Administrators\Administrator;
use VictorOpusculo\Eadpi\Lib\Model\Database\Connection;
use VictorOpusculo\PComp\RouteHandler;

require_once __DIR__ . '/../../lib/Middlewares/JsonBodyParser.php';

final class Login extends RouteHandler
{
    public function __construct()
    {
        $this->middlewares[] = '\VictorOpusculo\Eadpi\Lib\Middlewares\jsonParser';
    }

    protected function POST(): void
    {
        $conn = Connection::get();
        try
        {
            session_name("eadpi_admin_user");
            session_start();

            $adminGetter = new Administrator([ 'email' => $_POST['data']['email'] ?? 'n@d' ]);
            $admin = $adminGetter->getByEmail($conn);

            if ($admin->checkPassword($_POST['data']['password'] ?? '***'))
            {
                $_SESSION['user_type'] = UserTypes::administrator;
                $_SESSION['user_id'] = $admin->id->unwrap();
                $_SESSION['user_email'] = $admin->email->unwrap();
                $_SESSION['user_name'] = $admin->full_name->unwrapOr("Nome não definido");
                LogEngine::writeLog("Log-in de administrador realizado: ID {$admin->id->unwrapOr(0)}");
                $this->json([ 'success' => 'Bem-vindo!' ]);
            }
            else
                throw new Exception("Senha incorreta!");
        }
        catch (Exception $e)
        {
            LogEngine::writeErrorLog($e->getMessage());
            session_unset();
            if (isset($_SESSION)) session_destroy();
            $this->json([ 'error' => $e->getMessage() ], 500);
        }
        exit;
    }
}