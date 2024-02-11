<?php
namespace VictorOpusculo\Eadpi\App\Students;

use VictorOpusculo\Eadpi\Components\Layout\DefaultPageFrame;
use VictorOpusculo\PComp\Component;
use VictorOpusculo\PComp\HeadManager;

use function VictorOpusculo\PComp\Prelude\component;
use function VictorOpusculo\PComp\Prelude\tag;
use function VictorOpusculo\PComp\Prelude\text;

class RecoverPassword extends Component
{
    protected function setUp()
    {
        HeadManager::$title = "Recuperar acesso";
    }

    protected function markup(): Component|array|null
    {
        return component(DefaultPageFrame::class, children: 
        [
            tag('h1', children: text('Recuperar acesso')),
            tag('student-recover-password')
        ]);
    }
}