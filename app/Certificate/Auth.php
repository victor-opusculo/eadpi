<?php
namespace VictorOpusculo\Eadpi\App\Certificate;

use VictorOpusculo\Eadpi\Components\Layout\DefaultPageFrame;
use VictorOpusculo\PComp\Component;
use VictorOpusculo\PComp\HeadManager;

use function VictorOpusculo\PComp\Prelude\component;
use function VictorOpusculo\PComp\Prelude\tag;
use function VictorOpusculo\PComp\Prelude\text;

class Auth extends Component
{
    protected function setUp()
    {
        HeadManager::$title = "Verificar certificado";
    }

    protected function markup(): Component|array|null
    {
        return component(DefaultPageFrame::class, children: 
        [
            tag('h1', children: text('Verificar certificado')),
            tag('certificate-auth-form')
        ]);
    }
}