<?php

namespace VictorOpusculo\Eadpi\App\Students\Panel;

use VictorOpusculo\PComp\Component;
use VictorOpusculo\PComp\HeadManager;

use function VictorOpusculo\PComp\Prelude\tag;
use function VictorOpusculo\PComp\Prelude\text;

final class PanelHome extends Component
{
    public function setUp()
    {
        HeadManager::$title = "Meu Aprendizado";
    }

    protected function markup(): Component|array|null
    {
        return 
        [
            tag('h1', children: text('Meu aprendizado'))
        ];
    }
}