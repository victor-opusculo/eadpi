<?php

namespace VictorOpusculo\Eadpi\App;

use VictorOpusculo\PComp\Component;
use VictorOpusculo\PComp\HeadManager;

use function VictorOpusculo\PComp\Prelude\scTag;
use function VictorOpusculo\PComp\Prelude\tag;
use function VictorOpusculo\PComp\Prelude\text;

final class HomePage extends Component
{
    public function setUp()
    {
        HeadManager::$title = "Plataforma EAD da Escola do Parlamento de Itapevi \"Doutor Osmar de Souza\"";
    }

    protected function markup(): Component|array|null
    {
        return 
        [
            tag('h1', children: text("Bem vindo!")),
            tag('p', class: 'text-justify mx-auto max-w-[700px] p-2', children: text(
                "Esta é a plataforma EAD da Escola do Parlamento da Câmara Municipal de Itapevi. " .
                "Atualmente temos somente o curso de Democracia e Cidadania disponível. Veja mais informações e se inscreva por meio da página de cursos."
            ))
        ];
    }
}