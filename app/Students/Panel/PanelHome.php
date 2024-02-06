<?php

namespace VictorOpusculo\Eadpi\App\Students\Panel;

use VictorOpusculo\Eadpi\Components\Students\SubscriptionCard;
use VictorOpusculo\Eadpi\Lib\Model\Database\Connection;
use VictorOpusculo\Eadpi\Lib\Model\Students\Student;
use VictorOpusculo\Eadpi\Lib\Model\Students\Subscription;
use VictorOpusculo\PComp\Component;
use VictorOpusculo\PComp\HeadManager;

use function VictorOpusculo\PComp\Prelude\component;
use function VictorOpusculo\PComp\Prelude\tag;
use function VictorOpusculo\PComp\Prelude\text;

final class PanelHome extends Component
{
    public function setUp()
    {
        HeadManager::$title = "Meu Aprendizado";

        $conn = Connection::get();
        $studentGetter = (new Student([ 'id' => $_SESSION['user_id'] ]));
        $studentGetter->setCryptKey(Connection::getCryptoKey());
        $student = $studentGetter->getSingle($conn);
        $student->fetchSubscriptions($conn);
        $this->student = $student;
    }

    private Student $student;

    protected function markup(): Component|array|null
    {
        return 
        [
            tag('h1', children: text('Meu aprendizado')),
            tag('div', class: 'flex flex-wrap px-8', children: 
                array_map(fn(Subscription $subs) => component(SubscriptionCard::class, subscription: $subs), $this->student->subscriptions)
            )
        ];
    }
}