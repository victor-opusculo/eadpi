<?php
namespace VictorOpusculo\Eadpi\App\Admin\Panel\Partners\PartnerId;

use Exception;
use VictorOpusculo\Eadpi\Api\Administrator\Partners\PartnerId;
use VictorOpusculo\Eadpi\Components\Label;
use VictorOpusculo\Eadpi\Components\Layout\DefaultPageFrame;
use VictorOpusculo\Eadpi\Components\Panels\ConvenienceLinks;
use VictorOpusculo\Eadpi\Lib\Helpers\System;
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

final class View extends Component
{
    protected function setUp()
    {
        HeadManager::$title = "Ver parceiro";
        $conn = Connection::get();
        try
        {
            if (!Connection::isId($this->partnerId))
                throw new Exception("ID inválido!");

            $this->partner = (new Partner([ 'id' => $this->partnerId ]))
            ->getSingle($conn)
            ->fetchCertificateBgMedia($conn);
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
            tag('h1', children: text('Ver parceiro')),
            component(Label::class, labelBold: true, label: "ID", children: text($this->partner->id->unwrapOr(0))),
            component(Label::class, labelBold: true, label: "Nome", children: text($this->partner->name->unwrapOr(''))),
            component(Label::class, labelBold: true, lineBreak: true, label: "Descrição", children: 
                tag('div', class: 'whitespace-pre-line', children:
                    text($this->partner->description->unwrapOr(''))
                )
            ),
            component(Label::class, labelBold: true, label: "Início", children: text(date_create($this->partner->begin->unwrapOr('now'))->format('d/m/Y'))),
            component(Label::class, labelBold: true, label: "Fim", children: text(
                $this->partner->end->unwrapOr(false)
                    ? date_create($this->partner->end->unwrap())->format('d/m/Y')
                    : 'Parceria em vigor'
                ),
            ),
            component(Label::class, labelBold: true, label: "Link para registro de aluno vinculado ao parceiro", children:
                tag('a', class: 'link', href: URLGenerator::generatePageUrl("/students/register_fp/{$this->partner->id->unwrapOr(0)}"), children:
                    text(URLGenerator::getHttpProtocolName() . '://' . $_SERVER['HTTP_HOST'] . URLGenerator::generatePageUrl("/students/register_fp/{$this->partner->id->unwrapOr(0)}"))
                )
            ),
            component(Label::class, labelBold: true, label: "Fundo de certificado", lineBreak: true, children:
                isset($this->partner->certificateBg)
                    ? scTag('img', class: 'block h-auto max-w-full lg:max-w-[900px]', src: URLGenerator::generateFileUrl($this->partner->certificateBg->fileNameFromBaseDir()))
                    : text("Indefinido.")    
            ),
            component(ConvenienceLinks::class,
                editUrl: URLGenerator::generatePageUrl("/admin/panel/partners/{$this->partner->id->unwrapOr(0)}/edit"),
                deleteUrl: URLGenerator::generatePageUrl("/admin/panel/partners/{$this->partner->id->unwrapOr(0)}/delete")
            )
        ])
        : null;
    }
}