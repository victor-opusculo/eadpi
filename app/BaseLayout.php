<?php

namespace VictorOpusculo\Eadpi\App;

use VictorOpusculo\Eadpi\Components\PageMessages;
use VictorOpusculo\Eadpi\Lib\Helpers\URLGenerator;
use VictorOpusculo\PComp\Component;

use function VictorOpusculo\PComp\Prelude\component;
use function VictorOpusculo\PComp\Prelude\scTag;
use function VictorOpusculo\PComp\Prelude\tag;
use function VictorOpusculo\PComp\Prelude\text;

final class BaseLayout extends Component
{
    public function setUp()
    {
        
    }

    protected function markup(): Component|array|null
    {
        return 
        [
            tag('div', class: 'min-h-[calc(100vh-295px)]', children:
            [
                tag('header', class: 'text-center w-full block', children:
                [
                    scTag('img', src: URLGenerator::generateFileUrl('assets/pics/eadpi.svg'), width: 200, class: 'block mx-auto my-4' ),
                    component(\VictorOpusculo\Eadpi\Components\NavBar::class)
                ]),
                component(PageMessages::class),
                tag('main', class: 'block w-full', children: $this->children)
            ]),
            tag('div', id: 'logos', class: 'p-4 bottom-[100px] left-0 right-0 overflow-auto text-center', children:
            [
                tag('span', class: "bg-[url('pics/epi.png')] dark:bg-[url('pics/epi_white.png')] bg-contain bg-bottom bg-no-repeat h-[130px] w-[250px] inline-block"),
                tag('span', class: "bg-[url('pics/cmi.png')] dark:bg-[url('pics/cmi_white.png')] bg-contain bg-bottom bg-no-repeat h-[80px] w-[230px] inline-block"),
            ]),
            tag('footer', class: "bg-[url('pics/rodape-transp.png')] bg-repeat-x block left-0 right-0 h-[100px]")
        ];
    }
}