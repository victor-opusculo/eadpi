<?php
namespace VictorOpusculo\Eadpi\App\Admin\Panel\Media;

use Exception;
use VictorOpusculo\Eadpi\Components\Data\BasicSearchInput;
use VictorOpusculo\Eadpi\Components\Data\DataGrid;
use VictorOpusculo\Eadpi\Components\Data\OrderByLinks;
use VictorOpusculo\Eadpi\Components\Data\Paginator;
use VictorOpusculo\Eadpi\Components\Layout\DefaultPageFrame;
use VictorOpusculo\Eadpi\Lib\Helpers\Data;
use VictorOpusculo\Eadpi\Lib\Helpers\URLGenerator;
use VictorOpusculo\Eadpi\Lib\Model\Database\Connection;
use VictorOpusculo\Eadpi\Lib\Model\Media\Media;
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
        HeadManager::$title = "Mídias";
        $conn = Connection::get();
        try
        {
            $getter = new Media();
            $this->mediaCount = $getter->getCount($conn, $_GET['q'] ?? '');
            $medias = $getter->getMultiple($conn, $_GET['q'] ?? '', $_GET['order_by'] ?? '', $_GET['page_num'] ?? 1, self::NUM_RESULTS_ON_PAGE);
            $this->medias = array_map(fn(Media $m) =>
            [
                'ID' => $m->id->unwrapOr(0),
                'Nome' => $m->name->unwrapOr(''),
                'Descrição' => Data::truncateText($m->description->unwrapOr(''), 100),
                'Extensão' => $m->file_extension->unwrapOr('')
            ], $medias);
        }
        catch (Exception $e)
        {
            Context::getRef('page_messages')[] = $e->getMessage();
        }
    }

    private array $medias = [];
    private int $mediaCount = 0;
    public const NUM_RESULTS_ON_PAGE = 20;

    protected function markup(): Component|array|null
    {
        return component(DefaultPageFrame::class, children:
        [
            tag('h1', children: text("Mídias")),
            component(BasicSearchInput::class),
            component(OrderByLinks::class, linksDefinitions: [ 'ID' => 'id', 'Extensão' => 'file_extension', 'Nome' => 'name' ]),
            tag('div', class: 'my-2', children:
                tag('a', class: 'btn', href: URLGenerator::generatePageUrl('/admin/panel/media/create'), children: text("Nova"))
            ),
            component(DataGrid::class,
                dataRows: $this->medias,
                detailsButtonURL: URLGenerator::generatePageUrl('/admin/panel/media/{param}'),
                editButtonURL: URLGenerator::generatePageUrl('/admin/panel/media/{param}/edit'),
                deleteButtonURL: URLGenerator::generatePageUrl('/admin/panel/media/{param}/delete'),
                rudButtonsFunctionParamName: 'ID'
            ),
            component(Paginator::class,
                totalItems: $this->mediaCount,
                numResultsOnPage: self::NUM_RESULTS_ON_PAGE
            )
        ]);
    }
}