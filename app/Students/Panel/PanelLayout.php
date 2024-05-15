<?php

namespace VictorOpusculo\Eadpi\App\Students\Panel;

use VictorOpusculo\Eadpi\Lib\Helpers\URLGenerator;
use VictorOpusculo\Eadpi\Lib\Helpers\UserTypes;
use VictorOpusculo\PComp\Component;
use VictorOpusculo\PComp\HeadManager;

use function VictorOpusculo\PComp\Prelude\tag;
use function VictorOpusculo\PComp\Prelude\text;

final class PanelLayout extends Component
{
    protected function setUp()
    {
        session_name('eadpi_student_user');
        session_start();

        if (!isset( $_SESSION['user_type']) || $_SESSION['user_type'] != UserTypes::student)
        {
            session_unset();
            if (!isset($_SESSION)) session_destroy();
            header('location:' . URLGenerator::generatePageUrl('/students/login', [ 'messages' => 'Aluno não logado!' ]), true, 303);
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
                    tag('span', class: 'font-bold', children: text('Aluno(a) logado(a): ')),
                    text($_SESSION['user_name'] ?? '***'),
                    isset($_SESSION['user_partner_name'])
                        ? [
                            tag('span', class: 'font-bold ml-4', children: text("Parceria: ")),
                            text($_SESSION['user_partner_name'] ?? '***')
                          ]
                        : null,
                ]),
                tag('span', children:
                [
                    tag('a', class: 'btn mr-2 inline-block', href: URLGenerator::generatePageUrl('/students/panel/edit_profile'), children: text('Alterar perfil')),
                    tag('student-logout-button')
                ])
            ]),
            tag('div', children: $this->children)
        ];
    }
}