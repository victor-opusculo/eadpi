<?php 
namespace VictorOpusculo\Eadpi\Components;


use VictorOpusculo\PComp\{View, Component};
use function VictorOpusculo\PComp\Prelude\{tag, text};

class Label extends Component
{
    protected bool $lineBreak = false;
    protected string $label;
    protected bool $labelBold = false;
    protected bool $reverse = false;

    protected function markup() : Component|array|null
    {
        return tag('label', class: 'flex m-2 ' . ($this->lineBreak ? 'flex-col' : 'flex-row items-center'), children:
            !$this->reverse ? 
            [
                tag('span', class: 'shrink mr-2 text-base ' . ($this->labelBold ? 'font-bold' : ''), children: [ text($this->label . ': ') ]),
                tag('span', class: 'grow text-base flex flex-row flex-wrap', children: $this->children)
            ]
            : 
            [
                tag('span', class: 'text-base', children: $this->children),
                tag('span', class: 'ml-2 text-base' . ($this->labelBold ? 'font-bold' : ''), children: [ text($this->label) ])
            ]
        );
    }
}