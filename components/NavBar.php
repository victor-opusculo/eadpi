<?php
namespace VictorOpusculo\Eadpi\Components;

use VictorOpusculo\Eadpi\Components\Layout\DarkModeToggler;
use VictorOpusculo\Eadpi\Lib\Helpers\URLGenerator;
use VictorOpusculo\PComp\{Component, ScriptManager};
use function VictorOpusculo\PComp\Prelude\{tag, component, scTag, text};

class NavBar extends Component
{
    protected function setUp()
    {
       ScriptManager::registerScript('darkModeTogglerScript', 
            "window.addEventListener('load', () => document.getElementById('darkModeToggler').checked = window.localStorage.darkMode === '1');");
    } 

    protected function markup() : Component
    {
        return tag('nav',
        class: 'sticky z-10 top-0 bg-green-600 text-white font-bold text-xl flex flex-row flex-wrap justify-between dark:bg-green-900/40',
        children:
        [
            tag('div', children: 
            [
                component(NavBarItem::class, url: URLGenerator::generatePageUrl('/'), label: 'Início'),
                component(NavBarItem::class, url: URLGenerator::generatePageUrl('/courses'), label: 'Cursos'),
                component(NavBarItem::class, url: URLGenerator::generatePageUrl('/student_panel'), label: 'Meu aprendizado')
            ]),
            component(DarkModeToggler::class)
        ]);
    }
}