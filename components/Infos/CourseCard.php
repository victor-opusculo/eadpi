<?php 
namespace VictorOpusculo\Eadpi\Components\Infos;

use VictorOpusculo\Eadpi\Lib\Helpers\URLGenerator;
use VictorOpusculo\Eadpi\Lib\Model\Courses\Course;
use VictorOpusculo\Eadpi\Lib\Model\Database\Connection;
use VictorOpusculo\Eadpi\Lib\Model\Students\Subscription;
use VictorOpusculo\PComp\{View, Component};
use function VictorOpusculo\PComp\Prelude\{tag, text, scTag};

class CourseCard extends Component
{
    protected function setUp()
    {

    }

    protected Course $course;

    protected function markup() : Component|array|null
    {
        return tag('a', class: 'block overflow-auto relative p-2 mx-4 h-[300px] min-w-[300px] max-w-[400px] rounded border border-neutral-300 dark:border-neutral-700 hover:brightness-75', 
        href: URLGenerator::generatePageUrl("/infos/courses/{$this->course->id->unwrap()}"),
        children:
        [
            tag('div', class: 'relative left-0 right-0 bottom-0 top-0 w-full', children:
                $this->course->cover_image_url->unwrapOr(null) ?
                    scTag('img', class: 'absolute w-auto h-auto left-0 right-0 top-0 bottom-0', src: $this->course->cover_image_url->unwrap())
                :
                    text('Sem imagem!')
            ),
            tag('div', class: 'absolute bottom-0 left-0 right-0 z-10 dark:bg-neutral-700/50 bg-neutral-300/80 p-2 text-center', children: 
            [
                tag('div', children: text($this->course->name->unwrap())),
                tag('div', class: 'flex flex-row items-center justify-center' , children: 
                [
                    tag('span', children: text($this->course->hours->unwrap() . ' horas'))
                ])
            ])
        ]);
    }
}