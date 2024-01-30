<?php

namespace VOpus\PhpOrm;

require_once __DIR__ . '/Option.php';

final class Some extends Option
{
    public function __construct(mixed $value) { parent::__construct($value); }
    public function __toString() { return '(Option: Some(' . $this->value . '))'; }
}