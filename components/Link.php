<?php 
namespace VictorOpusculo\Eadpi\Components;

use VictorOpusculo\PComp\{View, Component};
use function VictorOpusculo\PComp\Prelude\{tag, text};

class Link extends Component
{
    protected string $url;
    protected string $class = '';

    protected function markup() : Component|array|null
    {
        return tag('a', class: $this->class, href: $this->url, children: $this->children );
    }
}