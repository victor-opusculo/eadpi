<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../lib/Helpers/LogEngine.php';

use VictorOpusculo\Eadpi\Lib\Helpers\LogEngine;
use VictorOpusculo\Eadpi\Lib\Helpers\URLGenerator;
use VictorOpusculo\Eadpi\Lib\Helpers\UserTypes;
use VictorOpusculo\Eadpi\Lib\Model\Courses\TestQuestion;
use VictorOpusculo\Eadpi\Lib\Model\Database\Connection;
use VictorOpusculo\Eadpi\Lib\Model\Students\CompletedTestQuestion;
use VictorOpusculo\Eadpi\Lib\Model\Courses\GeneratedCertificate;
use VictorOpusculo\Eadpi\Lib\Model\Students\Student;
use VictorOpusculo\Eadpi\Lib\Model\Students\Subscription;

function getHttpProtocolName()
{
    $isHttps = $_SERVER['HTTPS'] ?? $_SERVER['REQUEST_SCHEME'] ?? $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? null;
    $isHttps = $isHttps && (strcasecmp('on', $isHttps) == 0 || strcasecmp('https', $isHttps) == 0);
    return $isHttps ? 'https' : 'http';
}

define('AUTH_ADDRESS',  getHttpProtocolName() . "://" . $_SERVER["HTTP_HOST"] . URLGenerator::generatePageUrl("/certificate/auth"));
define('CERT_BG', __DIR__ . '/../../assets/certificates/certbg2023.jpg');
define('_SYSTEM_TTFONTS', __DIR__ . '/../../assets/fonts/');

$subsId = isset($_GET['subscription_id']) && is_numeric($_GET['subscription_id']) ? (int)$_GET['subscription_id'] : null;

if (!$subsId || $subsId < 1)
    die("ID inválido!");

class CertPDF extends tFPDF
{
    private DateTime $subscriptionDateTime;
    private DateTime $endDateTime;
    private string $bodyText;
    private string $studentName;
    private int $studentScoredPoints;
    private int $maxScorePossible;
    private int $minScoreRequired;
    private int $questionsAnswered;
    private int $totalQuestionCount;
    private int $hours;
    private array $authInfos;

    public function setData(DateTime $subscriptionDateTime,
                            DateTime $endDateTime,
                            string $bodyText,
                            string $studentName,
                            int $studentScoredPoints,
                            int $maxScorePossible,
                            int $minScoreRequired,
                            int $questionsAnswered,
                            int $totalQuestionCount,
                            int $hours,
                            array $authInfos) : void
    {
        $this->subscriptionDateTime = $subscriptionDateTime->setTimezone(new DateTimeZone("America/Sao_Paulo"));
        $this->endDateTime = $endDateTime->setTimezone(new DateTimeZone("America/Sao_Paulo"));
        $this->bodyText = $bodyText;
        $this->studentName = $studentName;
        $this->studentScoredPoints = $studentScoredPoints;
        $this->maxScorePossible = $maxScorePossible;
        $this->minScoreRequired = $minScoreRequired;
        $this->questionsAnswered = $questionsAnswered;
        $this->totalQuestionCount = $totalQuestionCount;
        $this->hours = $hours;
        $this->authInfos = $authInfos;

        $this->AddFont("freesans", "", "FreeSans-LrmZ.ttf", true); 
		$this->AddFont("freesans", "B", "FreeSansBold-Xgdd.ttf", true);
		$this->AddFont("freesans", "I", "FreeSansOblique-ol30.ttf", true);
    }

    public function drawFrontPage() : void
    {
        $this->AddPage();

        $this->Image(CERT_BG, 0, 0, 297, 210, "JPG"); // Face page
        $this->Image("../../assets/certificates/cmilogo.png", 5, 186, 40, null, "PNG"); // CMI logo

        $this->setY(75);
        $this->SetX(42.5);
        $this->SetFont('freesans', 'B', 24);
        $this->MultiCell(212, 13, $this->studentName, 0, "C"); //Student name

        $this->SetFont('freesans', '', 13);
        $this->SetX(42.5);
        $this->MultiCell(212, 6, $this->bodyText, 0, "C"); // Body text

        $this->Cell(0, 11, "Itapevi, " . $this->formatEndDate($this->endDateTime), 0, 2, "C");
    }

    public function drawBackPage()
    {
        $this->AddPage();
        $this->Image("../../assets/certificates/certbackbottom.png", 0, 160, 297, 0, "PNG"); // Back logos

        $grade = number_format(($this->studentScoredPoints / $this->maxScorePossible) * 100, 0);

        $this->SetFont('freesans', '', 12);
        $this->Cell(0, 6, "Questões respondidas: {$this->questionsAnswered} de {$this->totalQuestionCount}", 0, 1);
        $this->Cell(0, 6, "Pontuação: {$this->studentScoredPoints} de {$this->minScoreRequired} mínimo (máximo possível: {$this->maxScorePossible})", 0, 1);
        $this->Cell(0, 6, "Data de início: {$this->subscriptionDateTime->format('d/m/Y')}", 0, 1);
        $this->Cell(0, 6, "Data de término: {$this->endDateTime->format('d/m/Y')}", 0, 1);
        $this->Cell(0, 6, "Carga horária do curso: {$this->hours}h", 0, 1);
        $this->Cell(0, 6, "Nota final: $grade%", 0, 1);

        $this->drawAuthenticationInfo();
    }

    private function formatEndDate(DateTime $dateTime)
	{
		$monthNumber = (int)$dateTime->format("m");
		$monthName = "";
		switch ($monthNumber)
		{
			case 1: $monthName = "janeiro"; break;
			case 2: $monthName = "fevereiro"; break;
			case 3: $monthName = "março"; break;
			case 4: $monthName = "abril"; break;
			case 5: $monthName = "maio"; break;
			case 6: $monthName = "junho"; break;
			case 7: $monthName = "julho"; break;
			case 8: $monthName = "agosto"; break;
			case 9: $monthName = "setembro"; break;
			case 10: $monthName = "outubro"; break;
			case 11: $monthName = "novembro"; break;
			case 12: $monthName = "dezembro"; break;
		}
		
		$dayNumber = (int)$dateTime->format("j") === 1 ? ("1º") : $dateTime->format("j");
		
		return $dayNumber . " de " . ($monthName) . " de " . $dateTime->format("Y");
	}

    private function drawAuthenticationInfo()
	{
		$this->SetX(150);
		$this->SetY($this->GetPageHeight() - 42);
		$this->SetFont("freesans", "I", 11);
		
		$code = $this->authInfos["code"];
		$issueDateTime = $this->authInfos["issueDateTime"]->format("d/m/Y H:i:s");
				
		$authText = "Verifique a autenticidade deste certificado em: " . AUTH_ADDRESS . " e informe os seguintes dados: Código $code - Emissão inicial em $issueDateTime.";
		$this->MultiCell(200, 5, $authText, 0, "L");
	}

}

session_name('eadpi_student_user');
session_start();

if (!isset($_SESSION) || $_SESSION['user_type'] !== UserTypes::student)
    die("Aluno não logado!");

$conn = Connection::get();
$subscription = (new Subscription([ 'id' => $subsId ]))->getSingle($conn);
$subscription->fetchCourse($conn);
[ $questionsAnsweredCount, $scoredPoints ] = $subscription->getCompletedTestQuestionsCountAndScoredPoints($conn);


if ($subscription->student_id->unwrapOr(0) != $_SESSION['user_id'])
    die("Certificado não localizado!");

$studentGetter = new Student([ 'id' => $subscription->student_id->unwrapOrElse(fn() => throw new Exception("Aluno não localizado!")) ]);
$studentGetter->setCryptKey(Connection::getCryptoKey());
$student = $studentGetter->getSingle($conn);
$questionsTotalCount = $subscription->course->getQuestionsTotalCount($conn);
$maxScorePossible = (new TestQuestion)->getQuestionsTotalPoints($conn, $subscription->course->id->unwrap());
$endDateTime = (new CompletedTestQuestion([ 'subscription_id' => $subscription->id->unwrap() ]))->getLastCompletedTestDatetimeFromSubscription($conn);

$issueDateTime = new DateTime('now', new DateTimeZone('UTC'));
$genCertificate = (new GeneratedCertificate([ 'course_id' => $subscription->course->id->unwrap(), 'student_id' => $student->id->unwrap(), 'datetime' => $issueDateTime->format('Y-m-d H:i:s') ]));
$certId = 0;
if (!$genCertificate->existsByCourseAndStudent($conn))
{
    $result = $genCertificate->save($conn);
    if ($result['newId'])
        $certId = $result['newId'];
    else
    {
        LogEngine::writeErrorLog("Erro ao registrar certificado. Inscrição ID: {$subscription->id->unwrapOr(-1)}");
        die("Erro ao registrar o certificado!");
    }
}
else
{
    $cert = $genCertificate->getByCourseAndStudent($conn);
    $certId = $cert->id->unwrap();
    $issueDateTime = new DateTime($cert->datetime->unwrap(), new DateTimeZone("UTC"));
}

$conn->close();

if ($scoredPoints < $subscription->course->min_points_required_on_tests->unwrap())
    die("Você não foi aprovado neste curso!");

$pdf = new CertPDF('L', 'mm', 'A4');
$pdf->setData(  subscriptionDateTime: new DateTime($subscription->datetime->unwrap(), new DateTimeZone("UTC")),
                endDateTime: $endDateTime,
                bodyText: $subscription->course->certificate_text->unwrap(),
                studentName: $student->full_name->unwrap(),
                studentScoredPoints: $scoredPoints,
                maxScorePossible: $maxScorePossible,
                minScoreRequired: $subscription->course->min_points_required_on_tests->unwrap(),
                questionsAnswered: $questionsAnsweredCount,
                totalQuestionCount: $questionsTotalCount,
                hours: $subscription->course->hours->unwrap(),
                authInfos: [ 'code' => $certId, 'issueDateTime' => $issueDateTime->setTimezone(new DateTimeZone("America/Sao_Paulo")) ]
             );

$pdf->drawFrontPage();
$pdf->drawBackPage();

header('Content-Type: application/pdf');
header('Content-Disposition: filename="'. $subscription->course->name->unwrapOr('Curso') .'.pdf"');
echo $pdf->Output('S');

LogEngine::writeLog("Certificado gerado. ID: $certId. Curso ID: {$subscription->course->id->unwrapOr(-1)}");