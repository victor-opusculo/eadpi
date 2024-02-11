<?php

namespace VictorOpusculo\Eadpi\App\Admin\Panel;

use VictorOpusculo\Eadpi\Lib\Helpers\URLGenerator;
use VictorOpusculo\Eadpi\Lib\Helpers\UserTypes;
use VictorOpusculo\PComp\Component;

use function VictorOpusculo\PComp\Prelude\tag;
use function VictorOpusculo\PComp\Prelude\text;

final class PanelLayout extends Component
{
    protected function setUp()
    {
        session_name('eadpi_admin_user');
        session_start();

        if (!isset( $_SESSION['user_type']) || $_SESSION['user_type'] != UserTypes::administrator)
        {
            session_unset();
            if (!isset($_SESSION)) session_destroy();
            header('location:' . URLGenerator::generatePageUrl('/admin/login', [ 'messages' => 'Administrador não logado!' ]), true, 303);
            exit;
        }
    }

    protected function markup(): Component|array|null
    {
        return 
        [
            tag('div', class: 'p-2 bg-neutral-200 dark:bg-neutral-800 flex flex-row justify-between items-center', children:
            [
                tag('span', children:
                [ 
                    tag('span', class: 'font-bold', children: text('Administrador(a) logado(a): ')),
                    text($_SESSION['user_name'] ?? '***')
                ]),
                tag('span', children:
                [
                    tag('a', class: 'btn mr-2 inline-block', href: URLGenerator::generatePageUrl('/admin/panel'), children: text('Home')),
                    tag('a', class: 'btn mr-2 inline-block', href: URLGenerator::generatePageUrl('/admin/panel/edit_profile'), children: text('Alterar perfil')),
                    tag('admin-logout-button')
                ])
            ]),
            tag('div', children: $this->children)
        ];
    }
}