<?php
namespace VictorOpusculo\Eadpi\App\Students\Panel\Test\TestId;

use VictorOpusculo\Eadpi\Components\Layout\DefaultPageFrame;
use VictorOpusculo\Eadpi\Lib\Helpers\Data;
use VictorOpusculo\Eadpi\Lib\Helpers\URLGenerator;
use VictorOpusculo\Eadpi\Lib\Model\Courses\Lesson;
use VictorOpusculo\Eadpi\Lib\Model\Courses\Module;
use VictorOpusculo\Eadpi\Lib\Model\Courses\Test;
use VictorOpusculo\Eadpi\Lib\Model\Courses\TestQuestion;
use VictorOpusculo\Eadpi\Lib\Model\Database\Connection;
use VictorOpusculo\Eadpi\Lib\Model\Students\CompletedTestQuestion;
use VictorOpusculo\Eadpi\Lib\Model\Students\Subscription;
use VictorOpusculo\PComp\Component;
use VictorOpusculo\PComp\Context;

use function VictorOpusculo\PComp\Prelude\component;
use function VictorOpusculo\PComp\Prelude\rawText;
use function VictorOpusculo\PComp\Prelude\scTag;
use function VictorOpusculo\PComp\Prelude\tag;
use function VictorOpusculo\PComp\Prelude\text;

class QuestionId extends Component
{
    protected function setUp()
    {
        $conn = Connection::get();
        try
        {
            if (!is_numeric($this->questId) || !is_numeric($this->id))
                throw new \Exception("ID inválido.");

            $question = (new TestQuestion([ 'id' => $this->questId ]))->getSingle($conn);

            $test = (new Test([ 'id' => $question->test_id->unwrap() ]))->getSingle($conn);
            $this->test = $test;

            $subsGetter = new Subscription([ 'student_id' => $_SESSION['user_id'] ?? 0, 'course_id' => $test->course_id->unwrapOr(0) ]);
            $isSubscribed = $subsGetter->isStudentSubscribed($conn);

            if (!$isSubscribed)
                throw new \Exception("Você não está inscrito no curso deste teste.");

            $this->subscription = $subsGetter->getSingleFromStudentAndCourse($conn);
            $this->subscription->fetchCourse($conn);

            $this->question = $question;
            $this->allQuestions = $question->getAllFromTest($conn);
            $this->lastAttempt = (new CompletedTestQuestion([ 'question_id' => $this->questId, 'subscription_id' => $this->subscription->id->unwrapOr(0) ]))->getSingleFromQuestionIdAndSubscription($conn);
            
            if ($test->linked_to_type->unwrapOr('') === 'lesson' && $test->linked_to_id->unwrapOr(0))
            {
                $this->lesson = (new Lesson([ 'id' => $test->linked_to_id->unwrapOr(0) ]))->getSingle($conn);
                $this->module = (new Module([ 'id' => $this->lesson->module_id->unwrapOr(0) ]))->getSingle($conn);
            } 

            if ($test->linked_to_type->unwrapOr('') === 'module' && $test->linked_to_id->unwrapOr(0))
            {
                $this->module = (new Module([ 'id' => $test->linked_to_id->unwrapOr(0) ]))->getSingle($conn);
            } 

            if (isset($this->lastAttempt) && $this->lastAttempt->is_correct->unwrapOr(0))
            {
                $this->isCorrect = true;
            }

            if (isset($this->lastAttempt) && $this->lastAttempt->attempts->unwrapOr(0) >= 3)
            {
                $this->skipQuestion = true;
                throw new \Exception('Você ultrapassou o número máximo de tentativas para esta questão!');
            }

            if (isset($this->lastAttempt))
                $this->thisAttemptNumber = $this->lastAttempt->attempts->unwrapOr(0) + 1;

            usort($this->allQuestions, fn(TestQuestion $a, TestQuestion $b) => $a->id->unwrapOr(0) <=> $b->id->unwrapOr(0));
            foreach ($this->allQuestions as $quest)
            {
                if ($quest->id->unwrapOr(0) > $this->question->id->unwrapOr(0))
                {
                    $this->nextQuestion = $quest;
                    break;
                }
            }
        }
        catch (\Exception $e)
        {
            Context::getRef("page_messages")[] = $e->getMessage();
        }
    }

    protected $id; //Test Id
    protected $questId;
    private ?Test $test;
    private ?Subscription $subscription;
    private ?Module $module;
    private ?Lesson $lesson;
    private ?CompletedTestQuestion $lastAttempt = null;
    private ?TestQuestion $question; 
    private ?TestQuestion $nextQuestion = null;
    private array $allQuestions;
    private int $thisAttemptNumber = 1;
    private bool $isCorrect = false;
    private bool $skipQuestion = false;

    protected function markup(): Component|array|null
    {
        if (isset($this->question))
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

                tag('h1', children: text('Questão')),
                tag('p', class: 'text-sm text-right', children: !$this->skipQuestion ? text("Tentativa {$this->thisAttemptNumber} de 3") : text("Tentativas esgotadas!")),
                tag('p', class: 'text-lg text-justify mx-auto max-w-[700px] my-4', children: rawText($this->question->title->unwrapOr(''))),
                tag('form', method: 'post', action: URLGenerator::generateScriptUrl('student/test/process_answer.php', 
                    [ 'quest_id' => $this->question->id->unwrapOr(0), 'next_quest_id' => isset($this->nextQuestion) ? $this->nextQuestion->id->unwrapOr(0) : 0, 'module_id' => $this->module->id->unwrapOr(0) ]),
                    class: 'mx-auto max-w-[700px]',
                    children:
                    [
                        tag('ol', class: 'pl-4 list-decimal', children:
                            array_map(fn(string $opt, int $i) => tag('li', children:
                            [
                                tag('label', children:
                                [
                                    scTag('input', type: 'radio', name: 'questionOptions', required: true, value: $i, checked: ($this->isCorrect || $this->skipQuestion) && (array_search($i, $this->question->decodeCorrectAnswers()) !== false), disabled: $this->isCorrect || $this->skipQuestion),
                                    text(' ' . $opt)
                                ])
                            ]), $this->question->decodeOptions(), array_keys($this->question->decodeOptions())) 
                        ),

                        tag('div', class: 'text-center mt-4', children: 
                            match (true)
                            {
                                !$this->isCorrect && !$this->skipQuestion => 
                                    tag('button', type: 'submit', class: 'btn', children: text('Enviar')),
                                $this->isCorrect => 
                                [
                                    tag('p', class: 'my-2', children: text('Você já respondeu e acertou esta questão!')),
                                    (isset($this->nextQuestion) ?
                                        tag('a', class: 'btn', href: URLGenerator::generatePageUrl("/students/panel/test/{$this->id}/{$this->nextQuestion->id->unwrapOr(0)}"), children: text('Próxima questão'))
                                    :
                                        tag('a', class: 'btn', href: URLGenerator::generatePageUrl("/students/panel/module/{$this->module->id->unwrapOr(0)}"), children: text('Finalizar teste'))
                                    )
                                ],
                                $this->skipQuestion => 
                                [
                                    tag('p', class: 'my-2', children: text('Você esgotou o número de tentativas!')),
                                    (isset($this->nextQuestion) ?
                                        tag('a', class: 'btn', href: URLGenerator::generatePageUrl("/students/panel/test/{$this->id}/{$this->nextQuestion->id->unwrapOr(0)}"), children: text('Próxima questão'))
                                    :
                                        tag('a', class: 'btn', href: URLGenerator::generatePageUrl("/students/panel/module/{$this->module->id->unwrapOr(0)}"), children: text('Finalizar teste'))
                                    )
                                ],
                                default => null
                            }
                        )
                    ]
                )
            ]);
        else
            return null;
    }
}