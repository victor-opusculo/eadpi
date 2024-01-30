<?php
namespace VictorOpusculo\Eadpi\Components;

use VictorOpusculo\PComp\{View, StyleManager, Component};
use function VictorOpusculo\PComp\Prelude\{tag, component, text};

require_once "Link.php";

class NavBarItem extends Component
{
    protected string $url = "#";
    protected string $label;

    protected function setUp()
    {
       
    } 

    protected function markup() : Component
    {
        return tag('span',
        class: '',
        children:
        [
            component(Link::class, class: 'hover:bg-red-500 cursor-pointer inline-block px-4 py-1 md:py-2 dark:hover:bg-red-700' , url: $this->url, children: text($this->label))
        ]);
    }
}