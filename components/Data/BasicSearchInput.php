<?php
namespace VictorOpusculo\Eadpi\Components\Data;

use VictorOpusculo\Eadpi\Lib\Helpers\Data;
use VictorOpusculo\Eadpi\Lib\Helpers\URLGenerator;
use VictorOpusculo\PComp\Component;

use function VictorOpusculo\PComp\Prelude\scTag;
use function VictorOpusculo\PComp\Prelude\tag;
use function VictorOpusculo\PComp\Prelude\text;

class BasicSearchInput extends Component
{
    public function setUp()
    {
    }

	protected function markup(): Component|array|null
    {
        return tag('form', class: 'my-2', method: 'get', children:
        [
            tag('span', class: 'flex flex-row items-center', children:
            [
                !URLGenerator::$useFriendlyUrls ? scTag('input', type: 'hidden', name: 'page', value: $_GET['page'] ?? '/') : null,
                tag('label', children:
                [
                    text('Pesquisar: '),
                    scTag('input', type: 'search', size: 25, name: 'q', maxlength: 280, value: Data::hscq($_GET['q'] ?? '')),
                ]),
                tag('button', type: 'submit', class: 'btn min-w-0 ml-2 py-2', children: 
                [
                    scTag('img', alt: 'Pesquisar', src: URLGenerator::generateFileUrl('assets/pics/search.png'))
                ])
            ])
        ]);
    }
}