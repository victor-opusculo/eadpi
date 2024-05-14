<?php
namespace VictorOpusculo\Eadpi\App\Admin\Panel\Partners\PartnerId;

use Exception;
use VictorOpusculo\Eadpi\Components\Layout\DefaultPageFrame;
use VictorOpusculo\Eadpi\Lib\Helpers\Data;
use VictorOpusculo\Eadpi\Lib\Model\Database\Connection;
use VictorOpusculo\Eadpi\Lib\Model\Partners\Partner;
use VictorOpusculo\PComp\Component;
use VictorOpusculo\PComp\Context;
use VictorOpusculo\PComp\HeadManager;

use function VictorOpusculo\PComp\Prelude\component;
use function VictorOpusculo\PComp\Prelude\tag;
use function VictorOpusculo\PComp\Prelude\text;

final class Edit extends Component
{
    protected function setUp()
    {
        HeadManager::$title = "Editar parceiro";
        $conn = Connection::get();
        try
        {
            if (!Connection::isId($this->partnerId))
                throw new Exception("ID inválido!");

            $this->partner = (new Partner([ 'id' => $this->partnerId ]))->getSingle($conn);
        }
        catch (Exception $e)
        {
            Context::getRef('page_messages')[] = $e->getMessage();
        }
    }

    private ?Partner $partner = null;
    protected $partnerId;

    protected function markup(): Component|array|null
    {
        return component(DefaultPageFrame::class, children:
        [
            tag('h1', children: text('Editar parceiro')),
            tag('edit-partner-form',
                id: $this->partner->id->unwrapOr(0),
                name: Data::hscq($this->partner->name->unwrapOr('')),
                description: Data::hscq($this->partner->description->unwrapOr('')),
                begin: Data::hscq($this->partner->begin->unwrapOr('')),
                end: Data::hscq($this->partner->end->unwrapOr('')),
                certificate_bg_media_id: $this->partner->certificate_bg_media_id->unwrapOr(0)
            )
        ]);
    }
}