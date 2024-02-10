<?php
namespace VictorOpusculo\Eadpi\App\Infos\Courses;

use VictorOpusculo\Eadpi\Components\Infos\CourseCard;
use VictorOpusculo\Eadpi\Lib\Model\Courses\Course;
use VictorOpusculo\Eadpi\Lib\Model\Database\Connection;
use VictorOpusculo\PComp\Component;
use function VictorOpusculo\PComp\Prelude\{ component, tag, text };

class Home extends Component
{
    protected function setUp()
    {
        $conn = Connection::get();
        $this->courses = array_filter((new Course)->getAll($conn), fn(Course $c) => (bool)$c->is_visible->unwrapOr(0)); 
    }

    private array $courses = [];

    protected function markup(): Component|array|null
    {
        return 
        [
            tag('h1', children: text('Cursos')),
            tag('div', class: 'flex flex-wrap lg:px-8 px-4', children: 
                count($this->courses) > 0 ?
                    array_map(fn(Course $course) => component(CourseCard::class, course: $course), $this->courses)
                :
                    null
            )
        ];
    }
}