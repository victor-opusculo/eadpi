<?php

namespace VictorOpusculo\Eadpi\App\Students\Panel;

use VictorOpusculo\PComp\Component;
use VictorOpusculo\PComp\HeadManager;

use function VictorOpusculo\PComp\Prelude\tag;
use function VictorOpusculo\PComp\Prelude\text;

final class PanelLayout extends Component
{
    protected function markup(): Component|array|null
    {
        return 
        [
            tag('div', class: 'p-2 bg-neutral-200 dark:bg-neutral-800', children: text('Aluno logado: ****')),
            tag('div', children: $this->children)
        ];
    }
}