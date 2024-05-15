<?php
namespace VictorOpusculo\Eadpi\App\Admin\Panel\Partners\PartnerId;

use Exception;
use VictorOpusculo\Eadpi\Api\Administrator\Partners\PartnerId;
use VictorOpusculo\Eadpi\Components\Label;
use VictorOpusculo\Eadpi\Components\Layout\DefaultPageFrame;
use VictorOpusculo\Eadpi\Components\Panels\ConvenienceLinks;
use VictorOpusculo\Eadpi\Lib\Helpers\URLGenerator;
use VictorOpusculo\Eadpi\Lib\Model\Database\Connection;
use VictorOpusculo\Eadpi\Lib\Model\Partners\Partner;
use VictorOpusculo\PComp\Component;
use VictorOpusculo\PComp\Context;
use VictorOpusculo\PComp\HeadManager;

use function VictorOpusculo\PComp\Prelude\component;
use function VictorOpusculo\PComp\Prelude\scTag;
use function VictorOpusculo\PComp\Prelude\tag;
use function VictorOpusculo\PComp\Prelude\text;

final class Delete extends Component
{
    protected function setUp()
    {
        HeadManager::$title = "Excluir parceiro";
        $conn = Connection::get();
        try
        {
            if (!Connection::isId($this->partnerId))
                throw new Exception("ID inválido!");

            $this->partner = (new Partner([ 'id' => $this->partnerId ]))
            ->getSingle($conn);
        }
        catch (Exception $e)
        {
            Context::getRef('page_messages')[] = $e->getMessage();
        }
    }

    protected $partnerId;
    private ?Partner $partner = null;

    protected function markup(): Component|array|null
    {
        return isset($this->partner) ? component(DefaultPageFrame::class, children:
        [
            tag('h1', children: text('Excluir parceiro')),

            tag('delete-entity-form',
            deletescripturl: URLGenerator::generateApiUrl("/administrator/partners/{$this->partner->id->unwrapOr(0)}"),
            gobacktourl: "/admin/panel/partners",
            children:
            [
                tag('p', class: 'text-center my-2', children: text("Deseja realmente excluir este parceiro? Está operação é irreversível!")),

                component(Label::class, labelBold: true, label: "ID", children: text($this->partner->id->unwrapOr(0))),
                component(Label::class, labelBold: true, label: "Nome", children: text($this->partner->name->unwrapOr(''))),
                component(Label::class, labelBold: true, lineBreak: true, label: "Descrição", children: 
                    tag('div', class: 'whitespace-pre-line', children:
                        text($this->partner->name->unwrapOr(''))
                    )
                ),
                component(Label::class, labelBold: true, label: "Início", children: text(date_create($this->partner->begin->unwrapOr('now'))->format('d/m/Y'))),
                component(Label::class, labelBold: true, label: "Fim", children: text(
                    $this->partner->end->unwrapOr(false)
                        ? date_create($this->partner->begin->unwrap())->format('d/m/Y')
                        : 'Parceria em vigor'
                    ),
                ),
            ])
        ])
        : null;
    }
}