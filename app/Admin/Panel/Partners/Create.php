<?php
namespace VictorOpusculo\Eadpi\App\Admin\Panel\Partners;

use VictorOpusculo\Eadpi\Components\Layout\DefaultPageFrame;
use VictorOpusculo\PComp\Component;
use VictorOpusculo\PComp\HeadManager;

use function VictorOpusculo\PComp\Prelude\component;
use function VictorOpusculo\PComp\Prelude\tag;
use function VictorOpusculo\PComp\Prelude\text;

final class Create extends Component
{
    protected function setUp()
    {
        HeadManager::$title = "Novo parceiro";
    }

    protected function markup(): Component|array|null
    {
        return component(DefaultPageFrame::class, children:
        [
            tag('h1', children: text('Novo parceiro')),
            tag('edit-partner-form')
        ]);
    }
}