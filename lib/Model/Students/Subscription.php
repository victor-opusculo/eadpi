<?php

namespace VictorOpusculo\Eadpi\Lib\Model\Students;

use VOpus\PhpOrm\DataEntity;
use VOpus\PhpOrm\DataProperty;

class Subscription extends DataEntity
{
    public function __construct(?array $initialValues)
    {
        $this->properties = (object)
        [
            'id' => new DataProperty(null, fn() => null, DataProperty::MYSQL_INT),
            'student_id' => new DataProperty(null, fn() => null, DataProperty::MYSQL_INT),
            'course_id' => new DataProperty(null, fn() => null, DataProperty::MYSQL_INT),
            'datetime' => new DataProperty(null, fn() => gmdate("Y-m-d H:i:s"), DataProperty::MYSQL_STRING)
        ];

        parent::__construct($initialValues);
    }

    protected string $databaseTable = 'student_subscriptions';
    protected string $formFieldPrefixName = 'student_subscriptions';
    protected array $primaryKeys = ['id'];
}