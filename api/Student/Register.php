<?php

namespace VictorOpusculo\Eadpi\Api\Student;

use Exception;
use VictorOpusculo\Eadpi\Lib\Helpers\LogEngine;
use VictorOpusculo\Eadpi\Lib\Model\Database\Connection;
use VictorOpusculo\Eadpi\Lib\Model\Students\Student;
use VictorOpusculo\PComp\RouteHandler;

require_once __DIR__ . '/../../lib/Middlewares/JsonBodyParser.php';

final class Register extends RouteHandler
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
            $student = new Student();
            $student->setCryptKey(Connection::getCryptoKey());
            $student->fillPropertiesFromFormInput($_POST['data']);

            if ($student->existsEmail($conn))
                throw new Exception("O e-mail informado já está cadastrado!");

            $student->hashPassword($_POST['data']['students:password']);

           $result = $student->save($conn);
            
            if ($result['newId'])
                $this->json([ 'success' => 'Cadastro efetuado com sucesso! Você pode entrar com sua conta agora.' ]);
            else
                throw new Exception("Não foi possível criar o cadastro!");
        }
        catch (Exception $e)
        {
            LogEngine::writeErrorLog($e->getMessage());
            $this->json([ 'error' => $e->getMessage() ], 500);
        }
        exit;
    }
}