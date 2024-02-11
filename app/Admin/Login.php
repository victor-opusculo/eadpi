<?php

namespace VictorOpusculo\Eadpi\App\Admin;

use VictorOpusculo\Eadpi\Lib\Helpers\URLGenerator;
use VictorOpusculo\PComp\Component;
use VictorOpusculo\PComp\HeadManager;

use function VictorOpusculo\PComp\Prelude\tag;
use function VictorOpusculo\PComp\Prelude\text;

final class Login extends Component
{
    public function setUp()
    {
        HeadManager::$title = "Log-in de Administrador";
    }

    protected function markup(): Component|array|null
    {
        return 
        [
            tag('h1', children: text('Log-in de Administrador')),
            tag('admin-login-form')
        ];
    }
}