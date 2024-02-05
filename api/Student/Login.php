<?php

namespace VictorOpusculo\Eadpi\Api\Student;

use Exception;
use VictorOpusculo\Eadpi\Lib\Helpers\LogEngine;
use VictorOpusculo\Eadpi\Lib\Helpers\UserTypes;
use VictorOpusculo\Eadpi\Lib\Model\Database\Connection;
use VictorOpusculo\Eadpi\Lib\Model\Students\Student;
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
            $studentGetter = new Student([ 'email' => $_POST['data']['email'] ?? 'n@d' ]);
            $studentGetter->setCryptKey(Connection::getCryptoKey());
            $student = $studentGetter->getByEmail($conn);

            session_name("eadpi_student_user");
            session_start();
            if ($student->checkPassword($_POST['data']['password'] ?? '***'))
            {
                $_SESSION['user_type'] = UserTypes::student;
                $_SESSION['user_email'] = $student->email->unwrap();
                $_SESSION['user_name'] = $student->full_name->unwrapOr("Nome não definido");
                LogEngine::writeLog("Log-in de aluno realizado: ID {$student->id->unwrapOr(0)}");
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