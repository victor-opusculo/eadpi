<?php
namespace VictorOpusculo\Eadpi\Components\Layout;


use VictorOpusculo\PComp\Component;
use function VictorOpusculo\PComp\Prelude\tag;

class FlexSeparator extends Component
{
    protected function markup(): Component|array|null
    {
        return tag('div', class: 'md:w-[2px] w-auto h-[2px] md:h-auto bg-neutral-300 dark:bg-neutral-700 m-2');
    }
}