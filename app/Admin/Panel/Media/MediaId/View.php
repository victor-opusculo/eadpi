<?php
namespace VictorOpusculo\Eadpi\App\Admin\Panel\Media\MediaId;

use Exception;
use VictorOpusculo\Eadpi\Components\Label;
use VictorOpusculo\Eadpi\Components\Layout\DefaultPageFrame;
use VictorOpusculo\Eadpi\Components\Panels\ConvenienceLinks;
use VictorOpusculo\Eadpi\Lib\Helpers\Data;
use VictorOpusculo\Eadpi\Lib\Helpers\System;
use VictorOpusculo\Eadpi\Lib\Helpers\URLGenerator;
use VictorOpusculo\Eadpi\Lib\Model\Database\Connection;
use VictorOpusculo\Eadpi\Lib\Model\Media\Media;
use VictorOpusculo\PComp\Component;
use VictorOpusculo\PComp\Context;
use VictorOpusculo\PComp\HeadManager;

use function VictorOpusculo\PComp\Prelude\component;
use function VictorOpusculo\PComp\Prelude\rawText;
use function VictorOpusculo\PComp\Prelude\scTag;
use function VictorOpusculo\PComp\Prelude\tag;
use function VictorOpusculo\PComp\Prelude\text;

final class View extends Component
{
    protected function setUp()
    {
        HeadManager::$title = "Ver mídia";

        $conn = Connection::get();
        try
        {
            if (!Connection::isId($this->mediaId))
                throw new Exception("ID inválido!");

            $this->media = (new Media([ 'id' => $this->mediaId ]))->getSingle($conn);
            
            $this->fileName = System::systemBaseDir() . "/uploads/media/{$this->media->id->unwrapOr(0)}.{$this->media->file_extension->unwrapOr('')}";
            $this->mime = mime_content_type($this->fileName);
            $this->isImage = strpos($this->mime, 'image') !== false;
        }
        catch (\Exception $e)
        {
            Context::getRef('page_messages')[] = $e->getMessage();
        }
    }

    protected $mediaId;
    private ?Media $media = null;
    private bool $isImage;
    private string $mime;
    private string $fileName;

    protected function markup(): Component|array|null
    {
        return $this->media ? component(DefaultPageFrame::class, children:
        [
            tag('h1', children: text('Ver mídia')),
            tag('div', class: 'text-center my-4', children:
            [
                $this->isImage ? 
                    scTag('img', 
                        class: 'inline-block',
                        src: URLGenerator::generateFileUrl("uploads/media/{$this->media->id->unwrapOr(0)}.{$this->media->file_extension->unwrapOr('')}"),
                        width: 256
                    )
                : 
                    tag('span', class: 'italic text-lg', children: text('Sem visualização'))
            ]),
            component(Label::class, label: 'ID', labelBold: true, children: text($this->media->id->unwrapOr(0))),
            component(Label::class, label: 'Nome', labelBold: true, children: text($this->media->name->unwrapOr(''))),
            component(Label::class, label: 'Descrição', labelBold: true, lineBreak: true, children: rawText(nl2br(Data::hsc($this->media->description->unwrapOr(''))))),
            component(Label::class, label: 'Extensão', labelBold: true, children: text($this->media->file_extension->unwrapOr('') . " ({$this->mime})")),
            component(Label::class, label: 'Link de acesso', labelBold: true, children:
                tag('a', 
                    class: 'link', 
                    href: URLGenerator::generateFileUrl("uploads/media/{$this->media->id->unwrapOr(0)}.{$this->media->file_extension->unwrapOr('')}"),
                    children: text(URLGenerator::generateFileUrl("uploads/media/{$this->media->id->unwrapOr(0)}.{$this->media->file_extension->unwrapOr('')}"))
                )
            ),

            component(ConvenienceLinks::class, 
                editUrl: URLGenerator::generatePageUrl("/admin/panel/media/{$this->media->id->unwrapOr(0)}/edit"),
                deleteUrl: URLGenerator::generatePageUrl("/admin/panel/media/{$this->media->id->unwrapOr(0)}/delete")
            )
        ]) : null;
    }
}