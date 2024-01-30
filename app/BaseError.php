<?php

namespace VictorOpusculo\Eadpi\App;

use VictorOpusculo\PComp\Component;

use function VictorOpusculo\PComp\Prelude\tag;
use function VictorOpusculo\PComp\Prelude\text;

class BaseError extends Component
{
    public \Exception $exception;

    protected function markup(): Component|array|null
    {
        return tag('span', children: text($this->exception->getMessage()));
    }
}
