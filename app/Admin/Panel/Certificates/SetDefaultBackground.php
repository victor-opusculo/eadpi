<?php
namespace VictorOpusculo\Eadpi\App\Admin\Panel\Certificates;

use Exception;
use VictorOpusculo\Eadpi\Components\Layout\DefaultPageFrame;
use VictorOpusculo\Eadpi\Lib\Model\Database\Connection;
use VictorOpusculo\Eadpi\Lib\Model\Settings\DefaultCertificateBgMediaId;
use VictorOpusculo\PComp\Component;
use VictorOpusculo\PComp\HeadManager;

use function VictorOpusculo\PComp\Prelude\component;
use function VictorOpusculo\PComp\Prelude\tag;
use function VictorOpusculo\PComp\Prelude\text;

final class SetDefaultBackground extends Component
{
    protected function setUp()
    {
        HeadManager::$title = "Definir fundo padrão para certificados";
        $conn = Connection::get();
        try
        {
            $this->sett = (new DefaultCertificateBgMediaId)->getSingle($conn);
        }
        catch (Exception)
        {
            $this->sett = new DefaultCertificateBgMediaId();
        }
    }

    private ?DefaultCertificateBgMediaId $sett = null;

    protected function markup(): Component|array|null
    {
        return component(DefaultPageFrame::class, children:
        [
            tag('h1', children: text("Definir fundo padrão para certificados")),
            tag('set-default-cert-bg-form', current_id: $this->sett->value->unwrapOr('') )
        ]);
    }
}