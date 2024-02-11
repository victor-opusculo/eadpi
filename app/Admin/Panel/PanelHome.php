<?php

namespace VictorOpusculo\Eadpi\App\Admin\Panel;

use VictorOpusculo\Eadpi\Lib\Helpers\URLGenerator;
use VictorOpusculo\Eadpi\Lib\Model\Database\Connection;
use VictorOpusculo\Eadpi\Lib\Model\Administrators\Administrator;
use VictorOpusculo\PComp\Component;
use VictorOpusculo\PComp\HeadManager;

use function VictorOpusculo\PComp\Prelude\component;
use function VictorOpusculo\PComp\Prelude\tag;
use function VictorOpusculo\PComp\Prelude\text;

final class PanelHome extends Component
{
    public function setUp()
    {
        HeadManager::$title = "Painel de administração";

        $conn = Connection::get();
        $adminGetter = (new Administrator([ 'id' => $_SESSION['user_id'] ]));
        $admin = $adminGetter->getSingle($conn);
        $this->admin = $admin;
    }

    private Administrator $admin;

    protected function markup(): Component|array|null
    {
        return 
        [
            tag('h1', children: text('Painel de administração')),
            tag('div', class: 'flex flex-wrap lg:px-8 px-4 justify-center', children: 
            [
                tag('a', class: 'btn mr-2', href: URLGenerator::generateApiUrl('/administrator/report/course_subscriptions'), children: text('Baixar relatório de inscrições em cursos'))
            ])
        ];
    }
}