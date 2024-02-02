<?php

namespace VictorOpusculo\Eadpi\Lib\Model\Students;

use VOpus\PhpOrm\DataEntity;
use VOpus\PhpOrm\DataProperty;

class Student extends DataEntity
{
    public function __construct(?array $initialValues)
    {
        $this->properties = (object)
        [
            'id' => new DataProperty(null, fn() => null, DataProperty::MYSQL_INT, false),
            'email' => new DataProperty('txtEmail', fn() => null, DataProperty::MYSQL_STRING, true),
            'full_name' => new DataProperty('txtName', fn() => null, DataProperty::MYSQL_STRING, true),
            'password_hash' => new DataProperty(null, fn() => null, DataProperty::MYSQL_STRING, false)
        ];

        parent::__construct($initialValues);
    }

    protected string $databaseTable = 'students';
    protected string $formFieldPrefixName = 'students';
    protected array $primaryKeys = ['id']; 
}