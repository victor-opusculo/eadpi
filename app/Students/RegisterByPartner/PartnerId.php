<?php
namespace VictorOpusculo\Eadpi\App\Students\RegisterByPartner;

use VictorOpusculo\Eadpi\Lib\Helpers\URLGenerator;
use VictorOpusculo\PComp\Component;
use VictorOpusculo\PComp\HeadManager;

final class PartnerId extends Component
{
    protected function setUp()
    {
        header('location:' . URLGenerator::generatePageUrl('/students/register', [ 'part_id' => $this->partnerId ]), true, 303);
        exit;
    }

    protected $partnerId;

    protected function markup(): Component|array|null
    {
        return null;
    }
}