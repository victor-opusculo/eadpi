<?php

namespace VictorOpusculo\Eadpi\App\Students;

use VictorOpusculo\Eadpi\Lib\Helpers\URLGenerator;
use VictorOpusculo\PComp\Component;
use VictorOpusculo\PComp\HeadManager;

use function VictorOpusculo\PComp\Prelude\tag;
use function VictorOpusculo\PComp\Prelude\text;

final class Login extends Component
{
    public function setUp()
    {
        HeadManager::$title = "Log-in de Aluno";
    }

    protected function markup(): Component|array|null
    {
        return 
        [
            tag('h1', children: text('Log-in de Aluno')),
            tag('student-login-form'),
            tag('div', class: 'text-center', children:
            [
                tag('a', class: 'block link', children: text("Não tenho conta, registrar-me"), href: URLGenerator::generatePageUrl('/students/register')),
                tag('a', class: 'block bt-2 link', children: text("Esqueci minha senha"), href: URLGenerator::generatePageUrl('/students/recover_password'))
            ])
        ];
    }
}