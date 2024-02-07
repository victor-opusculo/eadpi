<?php
namespace VictorOpusculo\Eadpi\Components\Layout;

use VictorOpusculo\PComp\Component;

use function VictorOpusculo\PComp\Prelude\tag;

class DefaultPageFrame extends Component
{
    protected function markup(): Component|array|null
    {
        return tag('div', class: 'lg:mx-8 mx-4', children: $this->children);
    }
}