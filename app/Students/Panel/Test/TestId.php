<?php
namespace VictorOpusculo\Eadpi\App\Students\Panel\Test;

use VictorOpusculo\Eadpi\Components\Layout\DefaultPageFrame;
use VictorOpusculo\Eadpi\Lib\Model\Courses\Test;
use VictorOpusculo\Eadpi\Lib\Model\Database\Connection;
use VictorOpusculo\Eadpi\Lib\Model\Students\Subscription;
use VictorOpusculo\PComp\Component;
use VictorOpusculo\PComp\Context;
use VictorOpusculo\Eadpi\Lib\Helpers\URLGenerator;
use VictorOpusculo\Eadpi\Lib\Model\Courses\Lesson;
use VictorOpusculo\Eadpi\Lib\Model\Courses\Module;

use function VictorOpusculo\PComp\Prelude\component;
use function VictorOpusculo\PComp\Prelude\tag;
use function VictorOpusculo\PComp\Prelude\text;

class TestId extends Component
{
    protected function setUp()
    {
        $conn = Connection::get();
        try
        {
            if (!is_numeric($this->id))
                throw new \Exception("ID inválido!");

            $test = (new Test([ 'id' => $this->id ]))->getSingle($conn);
            $subsGetter = new Subscription([ 'student_id' => $_SESSION['user_id'] ?? 0, 'course_id' => $test->course_id->unwrapOr(0) ]);
            $isSubscribed = $subsGetter->isStudentSubscribed($conn);

            if (!$isSubscribed)
                throw new \Exception("Você não está inscrito no curso deste teste.");
            $test->fetchQuestions($conn);

            if (count($test->questions) < 1)
                throw new \Exception("Não há questões neste teste.");

            $this->subscription = $subsGetter->getSingleFromStudentAndCourse($conn);
 
            if ($test->linked_to_type->unwrapOr('') === 'lesson' && $test->linked_to_id->unwrapOr(0))
            {
                $this->lesson = (new Lesson([ 'id' => $test->linked_to_id->unwrapOr(0) ]))->getSingle($conn);
                $this->module = (new Module([ 'id' => $this->lesson->module_id->unwrapOr(0) ]))->getSingle($conn);
            } 

            if ($test->linked_to_type->unwrapOr('') === 'module' && $test->linked_to_id->unwrapOr(0))
            {
                $this->module = (new Module([ 'id' => $test->linked_to_id->unwrapOr(0) ]))->getSingle($conn);
            } 
        }
        catch (\Exception $e)
        {
            Context::getRef('page_messages')[] = $e->getMessage();
        }
    }

    protected $id;
    private ?Test $test;
    private ?Subscription $subscription;
    private ?Module $module;
    private ?Lesson $lesson;

    protected function markup(): Component|array|null
    {
        if (isset($this->test))
            return component(DefaultPageFrame::class, children:
            [
                tag('section', class: 'block rounded border border-neutral-300 dark:border-neutral-700 p-4 m-2 w-full bg-neutral-100 dark:bg-neutral-800', children:
                [
                    tag('a', 
                        class: 'link mr-2',
                        href: URLGenerator::generatePageUrl("/students/panel/subscription/{$this->subscription->id->unwrapOr(0)}"), 
                        children: text($this->subscription->course->name->unwrapOr("Curso sem nome"))
                    ),
                    isset($this->module) ?
                    [
                        text(' > '),
                        tag('a', 
                            class: 'link',
                            href: URLGenerator::generatePageUrl("/students/panel/module/{$this->module->id->unwrapOr(0)}"), 
                            children: text($this->module->title->unwrapOr("Módulo sem nome"))
                        ),
                        isset($this->lesson) ?
                        [
                            text(' > '),
                            tag('a', 
                                class: 'link',
                                href: URLGenerator::generatePageUrl("/students/panel/lesson/{$this->lesson->id->unwrapOr(0)}"), 
                                children: text($this->lesson->title->unwrapOr("Aula sem nome"))
                            )
                        ]
                        :
                        null
                    ]
                    :
                    null,
                    text(' > '),
                    tag('a', 
                        class: 'link',
                        href: URLGenerator::generatePageUrl("/students/panel/test/{$this->test->id->unwrapOr(0)}"), 
                        children: text($this->test->title->unwrapOr("Teste sem nome"))
                    )
                ]),

                tag('h1', children: text('Teste')),
                tag('p', class: 'text-center font-bold', children: text($this->test->title->unwrapOr("Teste sem nome"))),
                tag('p', class: 'text-center', children: text("Clique no botão abaixo para iniciar o teste.")),
                tag('a', class: 'btn mt-4', 
                    href: URLGenerator::generatePageUrl("/students/panel/test/{$this->test->id->unwrapOr(0)}/{$this->test->questions[0]->id->unwrapOr(0)}"), 
                    children: text('Iniciar')
                )
            ]);
        else
            return null;
    }
}