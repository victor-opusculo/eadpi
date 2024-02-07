<?php
namespace VictorOpusculo\Eadpi\App\Students\Panel\Lesson;

use VictorOpusculo\Eadpi\Components\Layout\DefaultPageFrame;
use VictorOpusculo\Eadpi\Components\Students\LessonBlockSection;
use VictorOpusculo\Eadpi\Lib\Model\Courses\Lesson;
use VictorOpusculo\Eadpi\Lib\Model\Courses\Module;
use VictorOpusculo\Eadpi\Lib\Model\Database\Connection;
use VictorOpusculo\Eadpi\Lib\Model\Students\Subscription;
use VictorOpusculo\PComp\Component;
use VictorOpusculo\Eadpi\Lib\Helpers\URLGenerator;
use VictorOpusculo\Eadpi\Lib\Model\Courses\LessonBlock;

use function VictorOpusculo\PComp\Prelude\{component, tag, text, rawText};
use VictorOpusculo\PComp\Context;
use VictorOpusculo\PComp\HeadManager;

class LessId extends Component
{
    protected function setUp()
    {
        $conn = Connection::get();
        HeadManager::$title = "Aula";
        try
        {
            if (!is_numeric($this->id))
                throw new \Exception("ID inválido!");

            $lesson = (new Lesson([ 'id' => $this->id ]))->getSingle($conn);
            $module = (new Module([ 'id' => $lesson->module_id->unwrapOr(0) ]))->getSingle($conn);
            $subscriptionGetter = new Subscription([ 'course_id' => $module->course_id->unwrapOr(0), 'student_id' => $_SESSION['user_id'] ]);
            
            if (!$subscriptionGetter->isStudentSubscribed($conn))
                throw new \Exception("Você não está inscrito no curso desta aula.");

            $this->lesson = $lesson;
            $this->lesson->fetchBlocks($conn);
            $this->module = $module;
            $this->subscription = $subscriptionGetter->getSingleFromStudentAndCourse($conn);
            $this->subscription->fetchCourse($conn);
            
            HeadManager::$title = $this->lesson->title->unwrapOr('Aula sem nome');

        }
        catch (\Exception $e)
        {
            Context::getRef('page_messages')[] = $e->getMessage();
        }
    }

    protected $id;
    private ?Lesson $lesson;
    private ?Module $module;
    private ?Subscription $subscription;

    protected function markup(): Component|array|null
    {
        if (isset($this->subscription, $this->module, $this->lesson))
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
                    text(' > '),
                    tag('a', 
                        class: 'link',
                        href: URLGenerator::generatePageUrl("/students/panel/lesson/{$this->lesson->id->unwrapOr(0)}"), 
                        children: text($this->lesson->title->unwrapOr("Aula sem nome"))
                    )
                ]),

                $this->lesson->presentation_html->unwrapOr(null) ?
                tag('section', class: 'block rounded border border-neutral-300 dark:border-neutral-700 p-4 m-2 w-full bg-neutral-100 dark:bg-neutral-800', children:
                [
                    rawText($this->lesson->presentation_html->unwrapOr(''))
                ])
                :
                null,

                tag('section', class: 'block rounded border border-neutral-300 dark:border-neutral-700 p-4 m-2 w-full bg-neutral-100 dark:bg-neutral-800', children:
                [
                    ...array_map(fn(LessonBlock $block) => component(LessonBlockSection::class, block: $block), $this->lesson->blocks) 
                ])
            ]);
        else
            return null;
    }
}