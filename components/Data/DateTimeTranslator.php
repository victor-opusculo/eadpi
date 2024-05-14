<?php
namespace VictorOpusculo\Eadpi\Components\Data;

use DateTimeZone;
use VictorOpusculo\PComp\Component;

use function VictorOpusculo\PComp\Prelude\text;

class DateTimeTranslator extends Component
{
    protected ?string $utcDateTime = null;
    protected ?string $isoDateTime = null;

    protected function markup(): Component|array|null
    {
        return text($this->utcDateTime 
            ?   date_create($this->utcDateTime, new DateTimeZone('UTC'))
                ->setTimezone(new DateTimeZone($_SESSION['user_timezone'] ?? 'America/Sao_Paulo'))
                ->format('d/m/Y H:i:s')
                . ' (' . ($_SESSION['user_timezone'] ?? 'America/Sao_Paulo') . ')'
            : ($this->isoDateTime
                ?   date_create($this->isoDateTime)
                    ->setTimezone(new DateTimeZone($_SESSION['user_timezone'] ?? 'America/Sao_Paulo'))
                    ->format('d/m/Y H:i:s')
                    . ' (' . ($_SESSION['user_timezone'] ?? 'America/Sao_Paulo') . ')'
                :
                    '***'
            )
        );
    }
}