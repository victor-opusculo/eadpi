<?php
namespace VictorOpusculo\Eadpi\App\Admin\Panel\Media\MediaId;

use Exception;
use Symfony\Component\Console\Descriptor\Descriptor;
use VictorOpusculo\Eadpi\Components\Layout\DefaultPageFrame;
use VictorOpusculo\Eadpi\Lib\Helpers\Data;
use VictorOpusculo\Eadpi\Lib\Model\Database\Connection;
use VictorOpusculo\Eadpi\Lib\Model\Media\Media;
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
        HeadManager::$title = "Editar mídia";

        $conn = Connection::get();
        try
        {
            if (!Connection::isId($this->mediaId))
                throw new Exception("ID inválido!");

            $this->media = (new Media([ 'id' => $this->mediaId ]))->getSingle($conn);
        }
        catch (\Exception $e)
        {
            Context::getRef('page_messages')[] = $e->getMessage();
        }
    }

    protected $mediaId;
    private ?Media $media = null;

    protected function markup(): Component|array|null
    {
        return $this->media ? component(DefaultPageFrame::class, children:
        [
            tag('h1', children: text('Editar mídia')),
            tag('new-media-form', 
                name: Data::hscq($this->media->name->unwrapOr('')),
                description: Data::hscq($this->media->description->unwrapOr('')),
                id: $this->media->id->unwrapOr(0)
            )
        ]) : null;
    }
}