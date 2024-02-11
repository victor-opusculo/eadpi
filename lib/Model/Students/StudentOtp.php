<?php
namespace VictorOpusculo\Eadpi\Lib\Model\Students;

use DateInterval;
use DateTime;
use DateTimeZone;
use mysqli;
use PHPMailer\PHPMailer\PHPMailer;
use VictorOpusculo\Eadpi\Lib\Helpers\Data;
use VOpus\PhpOrm\DataEntity;
use VOpus\PhpOrm\DataProperty;

class StudentOtp extends DataEntity
{
    public function __construct(?array $initialValues = null)
    {
        $this->properties = (object)
        [
            'id' => new DataProperty(null, fn() => null, DataProperty::MYSQL_INT),
            'student_id' => new DataProperty(null, fn() => null, DataProperty::MYSQL_INT),
            'otp' => new DataProperty(null, fn() => null, DataProperty::MYSQL_STRING),
            'expiry_datetime' => new DataProperty(null, 
                fn() => (new DateTime('now', new DateTimeZone('UTC')))->add(new DateInterval('PT15M'))->format('Y-m-d H:i:s'), 
                DataProperty::MYSQL_STRING
            )
        ];

        parent::__construct($initialValues);
    }

    protected string $databaseTable = 'student_otps';
    protected string $formFieldPrefixName = 'student_otps';
    protected array $primaryKeys = ['id'];

    public function verifyOtp(string $givenOtp) : bool
    {
        $hashed = $this->properties->otp->getValue()->unwrapOr('');
        return password_verify($givenOtp, $hashed);
    }

    public function verifyDatetime() : bool
    {
        $dt = $this->properties->expiry_datetime->getValue()->unwrapOr('2000-01-01 12:00:00');
        $dt = new DateTime($dt, new DateTimeZone('UTC'));
        $now = new DateTime('now', new DateTimeZone('UTC'));

        return $now <= $dt;
    }

    public function clearAllOtpsFromStudent(mysqli $conn) : void
    {
        $studentId = $this->properties->student_id->getValue()->unwrapOr(0);
        $stmt = $conn->prepare("DELETE FROM student_otps WHERE student_id = ?");
        $stmt->bind_param('i', $studentId);
        $stmt->execute();
        $stmt->close();
    }

    public static function createNow(int $studentId) : array
    {
        $otp = mt_rand(100000, 999999);
        $new = new self([ 'student_id' => $studentId, 'otp' => password_hash($otp, PASSWORD_DEFAULT) ]);
        $new->properties->expiry_datetime->resetValue();
        return [ $new, $otp ];
    }

    public static function sendEmail(string $otp, string $studentEmail, string $studentName) : void
    {
        $configs = Data::getMailConfigs();
        $mail = new PHPMailer();

        $mail->IsSMTP(); // Define que a mensagem ser� SMTP
        $mail->Host = $configs['host']; // Seu endere�o de host SMTP
        $mail->SMTPAuth = true; // Define que ser� utilizada a autentica��o -  Mantenha o valor "true"
        $mail->Port = $configs['port']; // Porta de comunica��o SMTP - Mantenha o valor "587"
        $mail->SMTPSecure = false; // Define se � utilizado SSL/TLS - Mantenha o valor "false"
        $mail->SMTPAutoTLS = true; // Define se, por padr�o, ser� utilizado TLS - Mantenha o valor "false"
        $mail->Username = $configs['username']; // Conta de email existente e ativa em seu dom�nio
        $mail->Password = $configs['password']; // Senha da sua conta de email
        // DADOS DO REMETENTE
        $mail->Sender = $configs['sender']; // Conta de email existente e ativa em seu dom�nio
        $mail->From = $configs['sender']; // Sua conta de email que ser� remetente da mensagem
        $mail->FromName = "EADPI - Ensino à Distância da Escola do Parlamento de Itapevi"; // Nome da conta de email
        // DADOS DO DESTINAT�RIO
        $mail->AddAddress($studentEmail, $studentName); // Define qual conta de email receber� a mensagem

        // Defini��o de HTML/codifica��o
        $mail->IsHTML(true); // Define que o e-mail ser� enviado como HTML
        $mail->CharSet = 'utf-8'; // Charset da mensagem (opcional)
        // DEFINI��O DA MENSAGEM
        $mail->Subject  = "EADPI - Recuperação de acesso à plataforma"; // Assunto da mensagem

        ob_start();
        $oneTimePassword = $otp;
        $__VIEW = 'student-recover-password-otp-message.php';
        require_once (__DIR__ . '/../../Mail/email-base-body.php');
        $emailBody = ob_get_clean();
        ob_end_clean();

        $mail->Body .= $emailBody;
        
        $sent = $mail->Send();

        $mail->ClearAllRecipients();

        // Exibe uma mensagem de resultado do envio (sucesso/erro)
        if (!$sent)
            throw new \Exception("Não foi possível enviar o e-mail! Detalhes do erro: " . $mail->ErrorInfo);
    } 

}