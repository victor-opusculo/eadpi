<?php
namespace VictorOpusculo\Eadpi\Api\Student\RecoverPassword;

require_once __DIR__ . '/../../../lib/Middlewares/JsonBodyParser.php';


use Exception;
use VictorOpusculo\Eadpi\Lib\Helpers\LogEngine;
use VictorOpusculo\Eadpi\Lib\Model\Database\Connection;
use VictorOpusculo\Eadpi\Lib\Model\Students\Student;
use VictorOpusculo\Eadpi\Lib\Model\Students\StudentOtp;
use VictorOpusculo\PComp\RouteHandler;

class ChangePassword extends RouteHandler
{
    public function __construct()
    {
        $this->middlewares[] = '\VictorOpusculo\Eadpi\Lib\Middlewares\jsonParser';
    }

    protected function POST() : void
    {
        $conn = Connection::get();
        try
        {
            $givenOtp = $_POST['data']['givenOtp'] ?? '';
            $otpId = $_POST['data']['otpId'] ?? 0;
            $newPassword = $_POST['data']['newPassword'] ?? '';

            $entity = (new StudentOtp([ 'id' => $otpId ]))->getSingle($conn);

            if (!$entity->verifyDatetime())
            {
                $this->json([ 'error' => "Código expirado! Ele expira após 15 minutos a partir do envio. Tente gerar um novo.", 'reset' => true ], 500);
                exit;
            }

            if (!$entity->verifyOtp($givenOtp))
            {
                $this->json([ 'error' => "Código incorreto! Tente novamente." ], 500);
                exit;
            }

            if (!$newPassword)
            {
                $this->json([ 'error' => 'Informe uma nova senha.' ], 500);
                exit;
            }

            $student = (new Student([ 'id' => $entity->student_id->unwrap() ]))->setCryptKey(Connection::getCryptoKey())->getSingle($conn);
            $student
            ->setCryptKey(Connection::getCryptoKey())
            ->hashPassword($newPassword);

            $result = $student->save($conn);

            if ($result['affectedRows'] > 0)
            {
                $entity->delete($conn);
                LogEngine::writeLog("Senha de aluno alterada com sucesso! Aluno ID: {$student->id->unwrapOr(0)}");
                $this->json([ 'success' => 'Sua senha foi alterada com sucesso!' ]);
            }
            else
            {
                $entity->delete($conn);
                LogEngine::writeLog("Erro ao salvar senha de aluno. Aluno ID: {$student->id->unwrapOr(0)}");
                $this->json([ 'error' => 'Não foi possível salvar a nova senha.', 'reset' => true ]);
            }
        }
        catch (Exception $e)
        {
            LogEngine::writeErrorLog($e->getMessage());
            $this->json([ 'error' => $e->getMessage() ]);
            exit;
        }
    }
}