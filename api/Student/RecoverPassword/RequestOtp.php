<?php
namespace VictorOpusculo\Eadpi\Api\Student\RecoverPassword;

require_once __DIR__ . '/../../../lib/Middlewares/JsonBodyParser.php';

use Exception;
use VictorOpusculo\Eadpi\Lib\Helpers\LogEngine;
use VictorOpusculo\Eadpi\Lib\Model\Database\Connection;
use VictorOpusculo\Eadpi\Lib\Model\Students\Student;
use VictorOpusculo\Eadpi\Lib\Model\Students\StudentOtp;
use VictorOpusculo\PComp\RouteHandler;

class RequestOtp extends RouteHandler
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
            $email = $_POST['data']['email'] ?? 'n@d';
            $studentGetter = (new Student([ 'email' => $email ]))->setCryptKey(Connection::getCryptoKey());

            if ($studentGetter->existsEmail($conn))
            {
                $student = $studentGetter->getByEmail($conn);
                [ $otpEntity, $otpNumber ] = StudentOtp::createNow($student->id->unwrap());
                StudentOtp::sendEmail($otpNumber, $student->email->unwrap(), $student->full_name->unwrap());
                $otpEntity->clearAllOtpsFromStudent($conn);
                $result = $otpEntity->save($conn);
                if ($result['newId'])
                {
                    LogEngine::writeLog("Requisição de recuperação de senha efetuada. E-mail: $email.");
                    $this->json([ 'success' => 'E-mail enviado com o código! Verifique seu e-mail, incluindo a pasta de spam/lixo eletrônico.', 'data' => [ 'otpId' => $result['newId'] ] ]);
                }
                else
                    throw new Exception("Erro ao gravar registro de OTP.");
            }
            else
            {
                throw new Exception("E-mail '$email' não encontrado! Caso ainda não seja cadastrado, crie uma conta.");
            }
        }
        catch (Exception $e)
        {
            LogEngine::writeErrorLog($e->getMessage());
            $this->json([ 'error' => $e->getMessage() ], 500);
            exit;
        }
    }
}