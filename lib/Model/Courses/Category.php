<?php

namespace VictorOpusculo\Eadpi\Lib\Model\Courses;

use VOpus\PhpOrm\DataEntity;
use VOpus\PhpOrm\DataProperty;

class Category extends DataEntity
{
    public function __construct(?array $initialValues)
    {
        $this->properties = (object)
        [
            'id' => new DataProperty(null, fn() => null, DataProperty::MYSQL_INT),
            'title' => new DataProperty('txtTitle', fn() => null, DataProperty::MYSQL_STRING),
            'icon_url' => new DataProperty('txtIconUrl', fn() => null, DataProperty::MYSQL_STRING),
        ];

        parent::__construct($initialValues);
    }

    protected string $databaseTable = 'categories';
    protected string $formFieldPrefixName = 'categories';
    protected array $primaryKeys = ['id']; 
}