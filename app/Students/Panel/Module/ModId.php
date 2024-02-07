<?php
namespace VictorOpusculo\Eadpi\App\Students\Panel\Module;

use Exception;
use VictorOpusculo\Eadpi\Components\Layout\DefaultPageFrame;
use VictorOpusculo\Eadpi\Lib\Helpers\URLGenerator;
use VictorOpusculo\Eadpi\Lib\Model\Courses\Lesson;
use VictorOpusculo\Eadpi\Lib\Model\Courses\Module;
use VictorOpusculo\Eadpi\Lib\Model\Database\Connection;
use VictorOpusculo\Eadpi\Lib\Model\Students\Subscription;
use VictorOpusculo\PComp\Component;
use VictorOpusculo\PComp\Context;
use VictorOpusculo\PComp\HeadManager;

use function VictorOpusculo\PComp\Prelude\{component, rawText, tag, text};

class ModId extends Component
{
    protected function setUp()
    {
        HeadManager::$title = "Módulo";

        $conn = Connection::get();
        try
        {
            if (!is_numeric($this->id))
                throw new Exception("ID inválido!");

            $module = (new Module([ 'id' => $this->id ]))->getSingle($conn);
            $subsGetter = (new Subscription([ 'student_id' => $_SESSION['user_id'] ?? 0, 'course_id' => $module->course_id->unwrapOr(0) ]));
            
            if (!$subsGetter->isStudentSubscribed($conn))
                throw new Exception("Você não está inscrito no curso deste módulo.");

            $this->subscription = $subsGetter->getSingleFromStudentAndCourse($conn);
            $this->subscription->fetchCourse($conn);
            $this->module = $module;
            $this->module->fetchLessons($conn);

            HeadManager::$title = $module->title->unwrapOr("Módulo sem nome");
        }
        catch (\Exception $e)
        {
            Context::getRef('page_messages')[] = $e->getMessage();
        }
    }

    protected $id;
    private ?Subscription $subscription;
    private ?Module $module;

    protected function markup(): Component|array|null
    {
        if (isset($this->subscription, $this->module))
            return component(DefaultPageFrame::class, children:
            [
                tag('section', class: 'block rounded border border-neutral-300 dark:border-neutral-700 p-4 m-2 w-full bg-neutral-100 dark:bg-neutral-800', children:
                [
                    tag('a', 
                        class: 'link mr-2',
                        href: URLGenerator::generatePageUrl("/students/panel/subscription/{$this->subscription->id->unwrapOr(0)}"), 
                        children: text($this->subscription->course->name->unwrapOr("Curso sem nome"))
                    ),
                    text(' > '),
                    tag('a', 
                        class: 'link',
                        href: URLGenerator::generatePageUrl("/students/panel/module/{$this->module->id->unwrapOr(0)}"), 
                        children: text($this->module->title->unwrapOr("Módulo sem nome"))
                    ),
                ]),

                $this->module->presentation_html->unwrapOr(null) ?
                tag('section', class: 'block rounded border border-neutral-300 dark:border-neutral-700 p-4 m-2 w-full bg-neutral-100 dark:bg-neutral-800', children:
                [
                    rawText($this->module->presentation_html->unwrapOr(''))
                ])
                :
                null,

                tag('section', class: 'block rounded border border-neutral-300 dark:border-neutral-700 p-4 m-2 w-full bg-neutral-100 dark:bg-neutral-800', children:
                [
                    tag('h2', children: text('Aulas')),
                    ...array_map(fn(Lesson $less) => 
                        tag('a', 
                            class: 'block cursor-pointer first-of-type:border border-b border-l border-r border-neutral-300 dark:border-neutral-700 hover:bg-neutral-300 dark:hover:bg-neutral-700 p-2 max-w-[700px] mx-auto', 
                            children: text($less->title->unwrapOr("Aula sem nome")),
                            href: URLGenerator::generatePageUrl('/students/panel/lesson/' . $less->id->unwrapOr(0))
                        ), $this->module->lessons)
                ])
            ]);
        else
            return null;
    }
}