<?php
namespace VictorOpusculo\Eadpi\App\Admin\Panel;

use VictorOpusculo\Eadpi\Components\Layout\DefaultPageFrame;
use VictorOpusculo\Eadpi\Lib\Model\Administrators\Administrator;
use VictorOpusculo\Eadpi\Lib\Model\Database\Connection;
use VictorOpusculo\PComp\Component;
use VictorOpusculo\PComp\Context;
use VictorOpusculo\PComp\HeadManager;

use function VictorOpusculo\PComp\Prelude\component;
use function VictorOpusculo\PComp\Prelude\tag;
use function VictorOpusculo\PComp\Prelude\text;

class EditProfile extends Component
{
    protected function setUp()
    {
        HeadManager::$title = "Editar perfil";
        
        $conn = Connection::get();
        try
        {  
            $this->admin = (new Administrator([ 'id' => $_SESSION['user_id'] ]))->getSingle($conn);     
        }
        catch (\Exception $e)
        {
            Context::getRef('page_messages')[] = $e->getMessage();
        }
    }

    protected ?Administrator $admin;

    protected function markup(): Component|array|null
    {
        return component(DefaultPageFrame::class, children:
        [
            tag('h1', children: text("Alterar perfil")),
            tag('admin-change-data-form',
                fullname: $this->admin->full_name->unwrapOr(''),
                email: $this->admin->email->unwrapOr(''),
                adminid: $this->admin->id->unwrapOr(0)
            )
        ]);
    }
}