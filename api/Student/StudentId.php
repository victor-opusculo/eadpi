<?php
namespace VictorOpusculo\Eadpi\Api\Student;

use Exception;
use VictorOpusculo\Eadpi\Lib\Helpers\LogEngine;
use VictorOpusculo\Eadpi\Lib\Model\Database\Connection;
use VictorOpusculo\Eadpi\Lib\Model\Students\Student;
use VictorOpusculo\PComp\RouteHandler;

require_once __DIR__ . '/../../lib/Middlewares/JsonBodyParser.php';

class StudentId extends RouteHandler
{
    public function __construct()
    {
        $this->middlewares[] = '\VictorOpusculo\Eadpi\Lib\Middlewares\jsonParser';
    }

    protected $id;

    protected function PUT() : void
    {
        $conn = Connection::get();
        try
        {
            if (!isset($this->id) || !Connection::isId($this->id))
                throw new Exception("ID inválido.");

            $student = (new Student([ 'id' => $this->id ]))->setCryptKey(Connection::getCryptoKey())->getSingle($conn);
            $student
            ->setCryptKey(Connection::getCryptoKey())
            ->fillPropertiesFromFormInput($_POST['data'] ?? []);

            if ($student->existsAnotherStudentWithEmail($conn))
                throw new Exception("O e-mail informado já está em uso por outro aluno.");

            if (!empty($_POST['data']['students:password']))
            {
                if (!$student->checkPassword($_POST['data']['students:currpassword'] ?? ''))
                    throw new Exception("Senha atual incorreta!");

                $student->hashPassword($_POST['data']['students:password']);
            }

            $result = $student->save($conn);
            if ($result['affectedRows'] > 0)
            {
                LogEngine::writeLog("Dados de aluno atualizados pelo próprio. Aluno ID: {$student->id->unwrapOr(0)}");
                $this->json([ 'success' => 'Dados atualizados com sucesso!' ]);
            }
            else
            {
                LogEngine::writeLog("Dados de aluno não atualizados. Ação executada pelo próprio aluno. Aluno ID: {$student->id->unwrapOr(0)}");
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