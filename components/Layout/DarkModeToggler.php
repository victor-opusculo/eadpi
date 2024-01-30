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
        return tag('label', class: 'flex flex-row items-center px-2', children: [ scTag('input', class: 'mr-2', id: 'darkModeToggler', type: 'checkbox'), text('Modo escuro') ]);
    }
}