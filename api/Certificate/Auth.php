<?php

namespace VictorOpusculo\Eadpi\Api\Certificate;

use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use Exception;
use VictorOpusculo\Eadpi\Lib\Model\Courses\GeneratedCertificate;
use VictorOpusculo\Eadpi\Lib\Model\Courses\TestQuestion;
use VictorOpusculo\Eadpi\Lib\Model\Database\Connection;
use VictorOpusculo\Eadpi\Lib\Model\Students\Student;
use VictorOpusculo\Eadpi\Lib\Model\Students\Subscription;
use VictorOpusculo\PComp\RouteHandler;
use VOpus\PhpOrm\Exceptions\DatabaseEntityNotFound;

class Auth extends RouteHandler
{
    protected function GET() : void
    {
        $certId = $_GET['code'] ?? 0;
        $certDateTime = new DateTime($_GET['datetime'] ?? '', new \DateTimeZone("America/Sao_Paulo"));
        $certDateTime->setTimezone(new \DateTimeZone("UTC"));
        
        $conn = Connection::get();
        try
        {
            $cert = (new GeneratedCertificate([ 'id' => $certId, 'datetime' => $certDateTime->format('Y-m-d H:i:s') ]))->getByIdAndDatetime($conn);
            $subscription = (new Subscription([ 'student_id' => $cert->student_id->unwrapOr(0), 'course_id' => $cert->course_id->unwrapOr(0) ]))->getSingleFromStudentAndCourse($conn);
            $student = (new Student([ 'id' => $cert->student_id->unwrapOr(0) ]))->setCryptKey(Connection::getCryptoKey())->getSingle($conn);

            $subscription->fetchCourse($conn);
            [ $questionsAnsweredCount, $scoredPoints ] = $subscription->getCompletedTestQuestionsCountAndScoredPoints($conn);
            $minScoreRequired = $subscription->course->min_points_required_on_tests->unwrapOr(0);
            $maxScorePossible = (new TestQuestion)->getQuestionsTotalPoints($conn, $subscription->course->id->unwrapOr(0));

            $this->json(
                [
                    'success' => 'Certificado válido!',
                    'data' =>
                    [
                        'courseName' => $subscription->course->name->unwrapOr("Curso sem nome"),
                        'studentName' => $student->full_name->unwrapOr(''),
                        'studentPoints' => $scoredPoints,
                        'minPointsRequired' => $minScoreRequired,
                        'maxPointsPossible' => $maxScorePossible,

                        'certId' => $certId,
                        'certDatetime' => DateTimeImmutable::createFromMutable($certDateTime)->setTimezone(new DateTimeZone("America/Sao_Paulo"))->format('d/m/Y H:i:s')
                    ]
                ]
            );
        }
        catch (DatabaseEntityNotFound $e)
        {
            $this->json([ 'error' => 'Certificado inválido ou não existente!' ]);
            exit;
        }
        catch (Exception $e)
        {
            $this->json([ 'error' => $e->getMessage() ], 500);
            exit;
        }
    } 
}