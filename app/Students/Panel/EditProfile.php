<?php
namespace VictorOpusculo\Eadpi\App\Students\Panel;

use VictorOpusculo\Eadpi\Components\Layout\DefaultPageFrame;
use VictorOpusculo\Eadpi\Lib\Model\Database\Connection;
use VictorOpusculo\Eadpi\Lib\Model\Settings\LgpdTermText;
use VictorOpusculo\Eadpi\Lib\Model\Settings\LgpdTermVersion;
use VictorOpusculo\Eadpi\Lib\Model\Students\Student;
use VictorOpusculo\PComp\Component;
use VictorOpusculo\PComp\Context;
use VictorOpusculo\PComp\HeadManager;
use VictorOpusculo\PComp\ScriptManager;

use function VictorOpusculo\PComp\Prelude\component;
use function VictorOpusculo\PComp\Prelude\tag;
use function VictorOpusculo\PComp\Prelude\text;

class EditProfile extends Component
{
    protected function setUp()
    {
        ScriptManager::registerScript('timeZonesList', 
        "
            EADPI.Time ??= {};
            EADPI.Time.TimeZones = [" . array_reduce(\DateTimeZone::listIdentifiers(), fn($prev, $dtz) => ($prev ? $prev . ',' : '') . "\"$dtz\"" ) . "];
        ");

        HeadManager::$title = "Editar perfil";
        
        $conn = Connection::get();
        try
        {  
            $this->student = (new Student([ 'id' => $_SESSION['user_id'] ]))->setCryptKey(Connection::getCryptoKey())->getSingle($conn);
            $this->lgpdTermVersion = (new LgpdTermVersion)->getSingle($conn)->value->unwrapOrElse(fn() => throw new \Exception('Não foi possível carregar a versão do termo LGPD.'));            
            $this->lgpdTermText = (new LgpdTermText)->getSingle($conn)->value->unwrapOrElse(fn() => throw new \Exception('Não foi possível carregar o termo LGPD.'));
        }
        catch (\Exception $e)
        {
            Context::getRef('page_messages')[] = $e->getMessage();
        }
    }

    protected ?int $lgpdTermVersion = null;
    protected ?string $lgpdTermText = '';
    protected ?Student $student;

    protected function markup(): Component|array|null
    {
        return component(DefaultPageFrame::class, children:
        [
            tag('h1', children: text("Alterar perfil")),
            tag('student-change-data-form',
                lgpdtermversion: $this->lgpdTermVersion, 
                fullname: $this->student->full_name->unwrapOr(''),
                email: $this->student->email->unwrapOr(''),
                studentid: $this->student->id->unwrapOr(0),
                timezone: $this->student->timezone->unwrapOr('America/Sao_Paulo'),
                children: tag('textarea', name: 'lgpdTerm', class: 'w-full min-h-[calc(100vh-180px)]', readonly: true, children: text($this->lgpdTermText))
            )
        ]);
    }
}