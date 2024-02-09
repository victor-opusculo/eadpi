<?php
namespace VictorOpusculo\Eadpi\App\Infos\Courses;

use Exception;
use VictorOpusculo\Eadpi\Components\Layout\DefaultPageFrame;
use VictorOpusculo\Eadpi\Lib\Helpers\UserTypes;
use VictorOpusculo\Eadpi\Lib\Model\Courses\Course;
use VictorOpusculo\Eadpi\Lib\Model\Database\Connection;
use VictorOpusculo\Eadpi\Lib\Model\Students\Subscription;
use VictorOpusculo\PComp\Component;
use VictorOpusculo\PComp\Context;
use VictorOpusculo\PComp\HeadManager;

use function VictorOpusculo\PComp\Prelude\component;
use function VictorOpusculo\PComp\Prelude\tag;
use function VictorOpusculo\PComp\Prelude\text;
use function VictorOpusculo\PComp\Prelude\scTag;

class CourseId extends Component
{
    protected function setUp()
    {
        $conn = Connection::get();
        try
        {
            HeadManager::$title = "Curso";

            if (!is_numeric($this->id))
                throw new Exception("ID inválido.");

            $this->course = (new Course([ 'id' => $this->id ]))->getSingle($conn);
            $this->courseContent = $this->course->getInfos($conn);

            HeadManager::$title = "Curso: " . $this->course->name->unwrapOr("Curso sem nome");

            session_name('eadpi_student_user');
            session_start();
            $this->studentLoggedIn = (($_SESSION['user_type'] ?? '') === UserTypes::student) && (!empty($_SESSION['user_id']));

            if ($this->studentLoggedIn)
            {
                $subsGetter = (new Subscription([ 'student_id' => $_SESSION['user_id'], 'course_id' => $this->id ]));
                $this->studentAlreadySubscribed = $subsGetter->isStudentSubscribed($conn);
            }

        }
        catch (Exception $e)
        {
            Context::getRef('page_messages')[] = $e->getMessage();
        }
    }

    protected $id;
    private bool $studentLoggedIn = false;
    private bool $studentAlreadySubscribed = false;
    private ?Course $course;
    private array $courseContent;

    protected function markup(): Component|array|null
    {
        if (isset($this->course))
            return component(DefaultPageFrame::class, children:
            [
                tag('h1', children: text($this->course->name->unwrapOr("Curso sem nome"))),
                tag('div', class: 'mx-auto max-w-[900px] flex flex-col md:flex-row', children:
                [
                    tag('div', class: 'flex-[30%] mr-8 rounded border border-neutral-300 dark:border-neutral-700 bg-neutral-100 dark:bg-neutral-800', children: 
                        $this->course->cover_image_url->unwrapOr(null) ?
                            scTag('img', class: 'max-w-auto max-h-auto w-full p-4', src: $this->course->cover_image_url->unwrap())
                        :
                            text('Sem imagem!')
                    ),
                    tag('div', class: 'flex-[70%] py-2 rounded border border-neutral-300 dark:border-neutral-700 bg-neutral-100 dark:bg-neutral-800', children:
                    [
                        tag('h3', class: 'block text-center font-bold', children: text('Conteúdo')),
                        tag('ul', class: 'list-disc pl-8', children:
                        [
                            tag('li', children: text("Carga horária de {$this->courseContent['hours']}h")),
                            tag('li', children: text("{$this->courseContent['modules']} módulos")),
                            tag('li', children: text("{$this->courseContent['lessons']} aulas")),
                            tag('li', children: text("{$this->courseContent['blocks']} blocos")),
                            tag('li', children: text("{$this->courseContent['tests']} testes"))
                        ]),
                        tag('div', class: 'text-center mt-4', children:
                            $this->studentLoggedIn ?
                                ($this->studentAlreadySubscribed ?
                                    tag('span', children: text('Você já está inscrito neste curso.'))
                                :
                                    tag('course-subscribe-button', courseid: $this->course->id->unwrap())
                                )
                            :
                                tag('span', children: text('Entre com sua conta de aluno para se inscrever neste curso.'))
                        )
                    ])
                ])
            ]);
        else
            return null;
    }
}