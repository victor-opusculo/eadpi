<?php
namespace VictorOpusculo\Eadpi\App\Students;

use DateTimeZone;
use Exception;
use VictorOpusculo\Eadpi\Lib\Model\Database\Connection;
use VictorOpusculo\Eadpi\Lib\Model\Settings\LgpdTermText;
use VictorOpusculo\Eadpi\Lib\Model\Settings\LgpdTermVersion;
use VictorOpusculo\PComp\Component;
use VictorOpusculo\PComp\Context;
use VictorOpusculo\PComp\HeadManager;
use VictorOpusculo\PComp\ScriptManager;

use function VictorOpusculo\PComp\Prelude\tag;
use function VictorOpusculo\PComp\Prelude\text;

class Register extends Component
{
    protected function setUp()
    {
        ScriptManager::registerScript('timeZonesList', 
        "
            EADPI.Time ??= {};
            EADPI.Time.TimeZones = [" . array_reduce(DateTimeZone::listIdentifiers(), fn($prev, $dtz) => ($prev ? $prev . ',' : '') . "\"$dtz\"" ) . "];
        ");

        HeadManager::$title = "Cadastro de aluno";
        
        $conn = Connection::get();
        try
        {  
            $this->lgpdTermVersion = (new LgpdTermVersion)->getSingle($conn)->value->unwrapOrElse(fn() => throw new Exception('Não foi possível carregar a versão do termo LGPD.'));            
            $this->lgpdTermText = (new LgpdTermText)->getSingle($conn)->value->unwrapOrElse(fn() => throw new Exception('Não foi possível carregar o termo LGPD.'));
        }
        catch (\Exception $e)
        {
            Context::getRef('page_messages')[] = $e->getMessage();
        }
    }

    protected ?int $lgpdTermVersion = null;
    protected ?string $lgpdTermText = '';

    protected function markup(): Component|array|null
    {
        return 
        [
            tag('h1', children: text('Registrar-se')),
            tag('student-register-form', 
                lgpdtermversion: $this->lgpdTermVersion, 
                children: tag('textarea', name: 'lgpdTerm', class: 'w-full min-h-[calc(100vh-180px)]', readonly: true, children: text($this->lgpdTermText))
            )
        ];
    }
}
