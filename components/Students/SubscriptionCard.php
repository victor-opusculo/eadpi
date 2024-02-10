<?php 
namespace VictorOpusculo\Eadpi\Components\Students;

use VictorOpusculo\Eadpi\Lib\Helpers\URLGenerator;
use VictorOpusculo\Eadpi\Lib\Model\Database\Connection;
use VictorOpusculo\Eadpi\Lib\Model\Students\Subscription;
use VictorOpusculo\PComp\{View, Component};
use function VictorOpusculo\PComp\Prelude\{tag, text, scTag};

class SubscriptionCard extends Component
{
    protected function setUp()
    {
        $conn = Connection::get();
        [ $countTests, $scoredPoints ] = $this->subscription->getCompletedTestQuestionsCountAndScoredPoints($conn);
        $allTestsCount = $this->subscription->course->getQuestionsTotalCount($conn);

        $this->allTestsCount = $allTestsCount;
        $this->completedTestsCount = $countTests;
        $this->scoredPoints = $scoredPoints;
    }

    protected Subscription $subscription;
    protected int $completedTestsCount = 0;
    protected int $scoredPoints = 0;
    protected int $allTestsCount = 0;

    protected function markup() : Component|array|null
    {
        return tag('a', class: 'block overflow-auto relative p-2 mx-4 h-[300px] min-w-[300px] max-w-[400px] rounded border border-neutral-300 dark:border-neutral-700 hover:brightness-75', 
        href: URLGenerator::generatePageUrl("/students/panel/subscription/{$this->subscription->id->unwrap()}"),
        children:
        [
            tag('div', class: 'relative left-0 right-0 bottom-0 top-0 w-full', children:
                $this->subscription->course->cover_image_url->unwrapOr(null) ?
                    scTag('img', class: 'absolute w-auto h-auto left-0 right-0 top-0 bottom-0', src: $this->subscription->course->cover_image_url->unwrap())
                :
                    text('Sem imagem!')
            ),
            tag('div', class: 'absolute bottom-0 left-0 right-0 z-10 dark:bg-neutral-700/50 bg-neutral-300/80 p-2 text-center', children: 
            [
                tag('div', children: text($this->subscription->course->name->unwrap())),
                tag('div', class: 'flex flex-row items-center' , children: 
                [
                    tag('progress', class: 'h-2 w-[calc(100%-60px)]', value: $this->completedTestsCount, max: $this->allTestsCount, children: text((floor($this->completedTestsCount / $this->allTestsCount) * 100) . '%')),
                    tag('span', class: 'w-[60px]', children: text(number_format(($this->completedTestsCount / $this->allTestsCount) * 100, 0) . '%'))
                ])
            ])
        ]);
    }
}