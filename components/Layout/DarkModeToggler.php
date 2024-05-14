<?php
namespace VictorOpusculo\Eadpi\Components\Layout;

use VictorOpusculo\PComp\{Component, ScriptManager};
use function VictorOpusculo\PComp\Prelude\{text, tag, scTag};

final class DarkModeToggler extends Component
{
    protected function setUp()
    {
        ScriptManager::registerScript('darkModeTogglerScript', 
            "window.addEventListener('load', () => 
            {
                document.getElementById('darkModeToggler').checked = window.localStorage.darkMode === '1';
                document.getElementById('darkModeToggler').onchange = event => 
                {
                    document.documentElement.classList.toggle('dark'); 
                    window.localStorage.darkMode = event.target.checked ? '1' : '0';
                };
            });");
    } 

    protected function markup(): Component|array|null
    {
        return tag('label', class: 'flex flex-row items-center px-2 cursor-pointer', title: 'Alternar modo claro/escuro', children: 
        [ 
            scTag('input', class: 'invisible peer', id: 'darkModeToggler', type: 'checkbox'), 
            tag('span', class: "inline-block w-[2rem] h-[2rem] transition-[background-image] duration-500 bg-contain peer-checked:bg-[url('pics/moon.svg')] bg-[url('pics/sun.svg')]") 
        ]);
    }
}