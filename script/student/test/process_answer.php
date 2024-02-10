<?php

use VictorOpusculo\Eadpi\Lib\Helpers\URLGenerator;
use VictorOpusculo\Eadpi\Lib\Helpers\UserTypes;
use VictorOpusculo\Eadpi\Lib\Model\Courses\Test;
use VictorOpusculo\Eadpi\Lib\Model\Courses\TestQuestion;
use VictorOpusculo\Eadpi\Lib\Model\Database\Connection;
use VictorOpusculo\Eadpi\Lib\Model\Students\CompletedTestQuestion;
use VictorOpusculo\Eadpi\Lib\Model\Students\Subscription;

require_once __DIR__ . '/../../../vendor/autoload.php';

session_name('eadpi_student_user');
session_start();

if (($_SESSION['user_type'] ?? '') === UserTypes::student && isset($_POST['questionOptions']))
{
    $questionId = $_GET['quest_id'] ?? 0;
    $nextQuestionId = $_GET['next_quest_id'] ?? 0;
    $moduleId = $_GET['module_id'] ?? 0;
    $answers = [ (int)$_POST['questionOptions'] ?? -1 ];

    $conn = Connection::create();
    $question = (new TestQuestion([ 'id' => $questionId ]))->getSingle($conn);
    $test = (new Test([ 'id' => $question->test_id->unwrapOr(0) ]))->getSingle($conn);
    $subscription = (new Subscription([ 'student_id' => $_SESSION['user_id'] ?? 0, 'course_id' => $test->course_id->unwrapOr(0) ]))->getSingleFromStudentAndCourse($conn);
    $completedTest = (new CompletedTestQuestion([ 'question_id' => $questionId, 'subscription_id' => $subscription->id->unwrapOr(0) ]))->getSingleFromQuestionIdAndSubscription($conn);

    if (isset($completedTest) && $completedTest->attempts->unwrapOr(0) >= 3)
    {
        header('location:' . URLGenerator::generatePageUrl("/students/panel/test/{$test->id->unwrapOr(0)}/{$question->id->unwrapOr(0)}", [ 'messages' => 'Você esgotou o número de tentativas' ]), true, 303);
        exit;
    }

    if (!isset($completedTest))
        $completedTest = new CompletedTestQuestion([ 'student_id' => $_SESSION['user_id'], 'question_id' => $questionId, 'subscription_id' => $subscription->id->unwrapOr(0) ]);

    $completedTest->registerAttempt($answers, $question->decodeCorrectAnswers());
    $result = $completedTest->save($conn);

    $messages = [];

    if ($result['affectedRows'] > 0)
    {
        if ($completedTest->is_correct->unwrapOr(0))
            $messages[] = "Parabéns! Você acertou esta questão!";
        else
            $messages[] = "Você errou!";
    }
    else
        $messages[] = "Não foi possível registrar sua resposta.";
    
    $conn->close();
    header('location:' . URLGenerator::generatePageUrl("/students/panel/test/{$test->id->unwrapOr(0)}/{$question->id->unwrapOr(0)}", [ 'messages' => implode('//', $messages) ]), true, 303);
}