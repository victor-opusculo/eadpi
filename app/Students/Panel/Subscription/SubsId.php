<?php

namespace VictorOpusculo\Eadpi\App\Students\Panel\Subscription;

use Exception;
use VictorOpusculo\Eadpi\Components\Label;
use VictorOpusculo\Eadpi\Components\Layout\DefaultPageFrame;
use VictorOpusculo\Eadpi\Components\Layout\FlexSeparator;
use VictorOpusculo\Eadpi\Lib\Helpers\URLGenerator;
use VictorOpusculo\Eadpi\Lib\Model\Courses\Module;
use VictorOpusculo\Eadpi\Lib\Model\Database\Connection;
use VictorOpusculo\Eadpi\Lib\Model\Students\Subscription;
use VictorOpusculo\PComp\Component;
use VictorOpusculo\PComp\Context;
use VictorOpusculo\PComp\HeadManager;

use function VictorOpusculo\PComp\Prelude\component;
use function VictorOpusculo\PComp\Prelude\scTag;
use function VictorOpusculo\PComp\Prelude\tag;
use function VictorOpusculo\PComp\Prelude\text;

class SubsId extends Component
{
    protected function setUp()
    {
        HeadManager::$title = "Curso";
        $conn = Connection::get();
        try
        {
            if (!is_numeric($this->id))
                throw new Exception("ID inválido!");

            $getter = new Subscription([ 'id' => $this->id, 'student_id' => $_SESSION['user_id'] ?? 0 ]);
            $this->subscription = $getter->getSingleFromIdAndStudent($conn);
            $this->subscription->fetchCourse($conn);
            $this->subscription->course->fetchModules($conn);
            [ $questionsAnsweredCount, $scored, $maxScorePossible ] = $this->subscription->getCompletedTestQuestionsCountAndScoredPoints($conn);
            $this->questionsAnsweredCount = $questionsAnsweredCount;
            $this->maxScorePossible = $maxScorePossible;
            $this->scored = $scored;
            $this->minScoreRequired = $this->subscription->course->min_points_required_on_tests->unwrapOr(-1);
            $this->questionsTotalCount = $this->subscription->course->getQuestionsTotalCount($conn);

            $this->approved = $this->scored >= $this->minScoreRequired;

            HeadManager::$title = "Curso: {$this->subscription->course->name->unwrapOr('Sem nome')}";
        }
        catch (\Exception $e)
        {
            Context::getRef('page_messages')[] = $e->getMessage();
        }
    }

    protected $id;
    private int $questionsTotalCount;
    private int $questionsAnsweredCount;
    private int $minScoreRequired;
    private int $maxScorePossible;
    private int $scored;
    private ?bool $approved;
    private ?Subscription $subscription;

    protected function markup(): Component|array|null
    {
        if (isset($this->subscription))
            return component(DefaultPageFrame::class, children:
            [
                tag('section', class: 'rounded border border-neutral-300 dark:border-neutral-700 p-4 m-2 w-full flex md:flex-row flex-col bg-neutral-100 dark:bg-neutral-800', children:
                [
                    tag('div', class: 'block w-[8rem] h-[8rem] relative', children:
                    [
                        scTag('img', 
                        class: 'absolute top-0 bottom-0 left-0 right-0 my-auto',
                        src: $this->subscription->course->cover_image_url->unwrapOrElse(fn() => URLGenerator::generateFileUrl('assets/pics/nopic.png'))
                        )
                    ]),
                    component(FlexSeparator::class),
                    tag('span', class: 'text-lg font-bold flex items-center max-w-[300px]', children: text($this->subscription->course->name->unwrapOr("***"))),
                    component(FlexSeparator::class),
                    tag('div', children:
                    [
                        component(Label::class, labelBold: true, label: "Progresso", children:
                        [
                            tag('progress', class: 'h-2 my-2 mr-2', value: $this->questionsAnsweredCount, max: $this->questionsTotalCount),
                            tag('span', class: '', children: text((number_format($this->questionsAnsweredCount / $this->questionsTotalCount * 100, 2, ',')) . '%'))
                        ]),
                        component(Label::class, labelBold: true, label: "Questões respondidas", children:
                        [
                            text($this->questionsAnsweredCount . ' de ' . $this->questionsTotalCount)
                        ]),
                        component(Label::class, labelBold: true, label: "Pontuação nos testes", children:
                        [
                            text($this->scored . ' de ' . $this->minScoreRequired . ' requerido')
                        ]),
                        component(Label::class, labelBold: true, label: "Resultado", children:
                        [
                            $this->questionsAnsweredCount >= $this->questionsTotalCount ?
                                tag('span', class: ($this->approved ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300'), children:
                                [
                                    scTag('img', width: 24, height: 24, class: 'inline-block mr-2', src: $this->approved ? URLGenerator::generateFileUrl('assets/pics/check.png') : URLGenerator::generateFileUrl('assets/pics/wrong.png')),
                                    text($this->approved ? 'Aprovado!' : 'Reprovado!')
                                ])
                            :
                                text("Em progresso")
                        ]),
                        $this->approved ?
                            component(Label::class, labelBold: true, label: "Certificado", children:
                            [
                                tag('a', class: 'btn', href: URLGenerator::generateScriptUrl('certificate/generate.php', [ 'subscription_id' => $this->subscription->id->unwrapOr(0) ]), children: text('Gerar'))
                            ])
                        :
                        null
                    ])
                ]),
                            
                tag('section', class: 'rounded border border-neutral-300 dark:border-neutral-700 p-4 m-2 w-full bg-neutral-100 dark:bg-neutral-800', children:
                [
                    tag('h2', children: text('Módulos')),
                    ...array_map(fn(Module $mod) => 
                        tag('a', 
                            class: 'block cursor-pointer first-of-type:border border-b border-l border-r border-neutral-300 dark:border-neutral-700 hover:bg-neutral-300 dark:hover:bg-neutral-700 p-2 max-w-[700px] mx-auto', 
                            children: text($mod->title->unwrapOr("Módulo sem nome")),
                            href: URLGenerator::generatePageUrl('/students/panel/module/' . $mod->id->unwrapOr(0))
                        ), $this->subscription->course->modules)
                ])
            ]);
        else
            return null;
    }
}