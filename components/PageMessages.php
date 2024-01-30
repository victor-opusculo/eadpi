<?php
namespace VictorOpusculo\Eadpi\Components;

use VictorOpusculo\PComp\{View, Component, Context};
use function VictorOpusculo\PComp\Prelude\{render, tag, text};

class PageMessages extends Component
{
    protected function setUp() {} 

    protected function markup() : Component|null
    {
        return null;
    }

    public function render(): void
    {
        $messages = Context::get('page_messages') ?? [];

        if (count($messages) > 0)
            render(
            [
                tag('div', 
                    class: 'rounded px-4 py-2 w-[400px] my-2 mx-auto bg-green-200 text-center text-xl dark:bg-green-800 dark:text-white', 
                    children: array_map(fn($m) => tag('p', children: [ text($m) ]), $messages )
                )
            ]);
    }
}