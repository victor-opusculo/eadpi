<?php
namespace VictorOpusculo\Eadpi\Lib\Model\Reports;

use DateTime;
use DateTimeZone;
use mysqli;
use VictorOpusculo\Eadpi\Lib\Helpers\Data;
use VictorOpusculo\Eadpi\Lib\Model\Database\Connection;
use VOpus\PhpOrm\SqlSelector;

class CourseSubscriptions
{
    public function __construct()
    {  
    }

    private array $data;

    public function fetchData(mysqli $conn) : void
    {
        $cryptoKey = Connection::getCryptoKey();

        $selector = (new SqlSelector)
        ->addSelectColumn('student_subscriptions.id AS subscriptionId')  
        ->addSelectColumn('student_subscriptions.course_id As courseId')  
        ->addSelectColumn('student_subscriptions.datetime as subscriptionDatetime')  
        ->addSelectColumn('students.id as studentId')  
        ->addSelectColumn("aes_decrypt(students.full_name, '$cryptoKey') as studentFullName")  
        ->addSelectColumn("aes_decrypt(students.email, '$cryptoKey') as studentEmail")  
        ->addSelectColumn("(select count(DISTINCT student_completed_test_questions.id) from student_completed_test_questions where student_id = students.id) as answeredQuestions")  
        ->addSelectColumn("(select count(DISTINCT test_questions.id) 
        from test_questions inner join course_tests on course_tests.course_id = student_subscriptions.course_id 
        where test_questions.test_id = course_tests.id) as totalQuestions") 
        ->addSelectColumn("(select sum(if(student_completed_test_questions.is_correct, test_questions.points, 0)) 
        from student_completed_test_questions
        INNER join test_questions on test_questions.id = student_completed_test_questions.question_id
        where student_completed_test_questions.student_id = students.id) as scoredPoints")
        ->addSelectColumn("(select sum(test_questions.points) 
        from test_questions
       inner join course_tests on course_tests.id = test_questions.test_id
        where course_tests.course_id = student_subscriptions.course_id
        ) as maxScorePossible")
        ->addSelectColumn("courses.name AS courseName")
        ->setTable('student_subscriptions')
        ->addJoin("LEFT JOIN students ON students.id = student_subscriptions.student_id")
        ->addJoin("LEFT JOIN courses ON courses.id = student_subscriptions.course_id");

        $this->data = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
    }

    public function output() : void
    {
        if (!isset($this->data))
            die('Não há dados carregados!');

        if (count($this->data) < 1)
            die("Não há dados disponíveis.");

        header('Content-Encoding: UTF-8');
        header("Content-type: text/csv; charset=UTF-8");
        header("Content-Disposition: attachment; filename=inscricoes_ead.csv");

        $procData = Data::transformDataRows($this->data,
        [
            'Curso' => fn($dr) => ($dr['courseName'] ?? '') . ' (ID: ' . ($dr['courseId'] ?? '') . ')',
            'Inscrição ID' => fn($dr) => $dr['subscriptionId'] ?? '',
            'Data de inscrição' => fn($dr) => (new DateTime($dr['subscriptionDatetime'], new DateTimeZone("UTC")))->setTimezone(new DateTimeZone("America/Sao_Paulo"))->format('d/m/Y H:i:s'),
            'Aluno' => fn($dr) => "$dr[studentFullName] (ID: $dr[studentId])",
            'E-mail do aluno' => fn($dr) => $dr['studentEmail'] ?? '',
            'Questões respondidas' => fn($dr) => $dr['answeredQuestions'] ?? '',
            'Total de questões' => fn($dr) => $dr['totalQuestions'] ?? '',
            'Pontuação' => fn($dr) => $dr['scoredPoints'] ?? '',
            'Pontuação máxima' => fn($dr) => $dr['maxScorePossible'] ?? ''
         ]);

        $output = fopen("php://output", "w");
        $header = array_keys($procData[0]);

        fwrite($output, "\xEF\xBB\xBF" . PHP_EOL);
        fputcsv($output, $header, ";");

        foreach($procData as $row)
            fputcsv($output, $row, ";");

        fclose($output);
    }

}