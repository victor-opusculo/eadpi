<?php
namespace VictorOpusculo\Eadpi\App\Admin\Panel\Partners;

use Exception;
use VictorOpusculo\Eadpi\Components\Data\BasicSearchInput;
use VictorOpusculo\Eadpi\Components\Data\DataGrid;
use VictorOpusculo\Eadpi\Components\Data\OrderByLinks;
use VictorOpusculo\Eadpi\Components\Data\Paginator;
use VictorOpusculo\Eadpi\Components\Layout\DefaultPageFrame;
use VictorOpusculo\Eadpi\Lib\Helpers\URLGenerator;
use VictorOpusculo\Eadpi\Lib\Model\Database\Connection;
use VictorOpusculo\Eadpi\Lib\Model\Partners\Partner;
use VictorOpusculo\PComp\Component;
use VictorOpusculo\PComp\Context;
use VictorOpusculo\PComp\HeadManager;

use function VictorOpusculo\PComp\Prelude\component;
use function VictorOpusculo\PComp\Prelude\tag;
use function VictorOpusculo\PComp\Prelude\text;

final class Home extends Component
{
    protected function setUp()
    {
        HeadManager::$title = "Parceiros";
        $conn = Connection::get();
        try
        {
            $getter = new Partner();
            $this->partCount = $getter->getCount($conn, $_GET['q'] ?? '');
            $partners = $getter->getMultiple($conn, $_GET['q'] ?? '', $_GET['order_by'] ?? '', $_GET['page_num'] ?? 1, self::NUM_RESULTS_ON_PAGE);
            $this->partners = array_map(fn(Partner $p) =>
            [
                'ID' => $p->id->unwrapOr(0),
                'Nome' => $p->name->unwrapOr(''),
                'Descrição' => $p->description->unwrapOr(''),
                'Início da parceria' => date_create($p->begin->unwrapOr('now'))->format('d/m/Y'),
                'Fim' => $p->end->unwrapOr(false) ? date_create($p->end->unwrapOr('now'))->format('d/m/Y') : '-'
            ], $partners);
        }
        catch (Exception $e)
        {
            Context::getRef('page_messages')[] = $e->getMessage();
        }
    }

    private int $partCount = 0;
    private array $partners = [];
    private const NUM_RESULTS_ON_PAGE = 20;

    protected function markup(): Component|array|null
    {
        return component(DefaultPageFrame::class, children:
        [
            tag('h1', children: text("Parceiros")),
            component(BasicSearchInput::class),
            component(OrderByLinks::class, linksDefinitions: [ 'ID' => 'id', 'Nome' => 'name', 'Início de parceria' => 'date' ]),
            tag('div', class: 'my-2', children:
                tag('a', class: 'btn', href: URLGenerator::generatePageUrl('/admin/panel/partners/create'), children: text("Novo"))
            ),
            component(DataGrid::class,
                dataRows: $this->partners,
                detailsButtonURL: URLGenerator::generatePageUrl('/admin/panel/partners/{param}'),
                editButtonURL: URLGenerator::generatePageUrl('/admin/panel/partners/{param}/edit'),
                deleteButtonURL: URLGenerator::generatePageUrl('/admin/panel/partners/{param}/delete'),
                rudButtonsFunctionParamName: 'ID'
            ),
            component(Paginator::class,
                totalItems: $this->partCount,
                numResultsOnPage: self::NUM_RESULTS_ON_PAGE
            )
        ]);
    }
}