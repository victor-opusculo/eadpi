<?php
namespace VictorOpusculo\Eadpi\Api\Student\Subscribe;

use VictorOpusculo\Eadpi\Lib\Helpers\UserTypes;
use VictorOpusculo\Eadpi\Lib\Model\Courses\Course;
use VictorOpusculo\Eadpi\Lib\Model\Database\Connection;
use VictorOpusculo\Eadpi\Lib\Model\Students\Subscription;
use VictorOpusculo\PComp\RouteHandler;

class CourseId extends RouteHandler
{
    protected $courseId;

    protected function POST(): void
    {
        session_name('eadpi_student_user');
        session_start();

        if ($_SESSION['user_type'] !== UserTypes::student)
        {
            $this->json([ 'error' => 'Você precisa estar logado como aluno!' ], 500);
            exit;
        }

        $conn = Connection::get();

        $courseExists = (new Course([ 'id' => $this->courseId ]))->exists($conn);
        if (!$courseExists)
        {
            $this->json([ 'error' => 'Curso não existente!'], 404);
            exit;
        }

        $newSubs = (new Subscription([ 'course_id' => $this->courseId, 'student_id' => $_SESSION['user_id'] ?? 0, 'datetime' => gmdate('Y-m-d H:i:s') ]));

        if ($newSubs->isStudentSubscribed($conn))
        {
            $this->json([ 'error' => 'Você já está inscrito neste curso!' ], 500);
            exit;
        }

        $result = $newSubs->save($conn);
        if ($result['newId'])
        {
            $this->json([ 'success' => 'Você se inscreveu no curso!' ]);
        }
        else
        {
            $this->json([ 'error' => 'Não foi possível se increver no curso. Contate o suporte da Escola do Parlamento.' ], 500);
        }
    }
}