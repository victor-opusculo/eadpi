<?php

namespace VictorOpusculo\Eadpi\Lib\Model\Students;

use VOpus\PhpOrm\DataEntity;
use VOpus\PhpOrm\DataProperty;

class CompletedTestQuestion extends DataEntity
{
    public function __construct(?array $initialValues)
    {
        $this->properties = (object)
        [
            'id' => new DataProperty(null, fn() => null, DataProperty::MYSQL_INT),
            'student_id' => new DataProperty(null, fn() => null, DataProperty::MYSQL_INT),
            'subscription_id' => new DataProperty(null, fn() => null, DataProperty::MYSQL_INT),
            'question_id' => new DataProperty(null, fn() => null, DataProperty::MYSQL_INT),
            'completed_at' => new DataProperty(null, fn() => gmdate("Y-m-d H:i:s"), DataProperty::MYSQL_STRING),
            'answers' => new DataProperty(null, fn() => null, DataProperty::MYSQL_STRING),
            'is_correct' => new DataProperty(null, fn() => null, DataProperty::MYSQL_INT),
            'attempts' => new DataProperty(null, fn() => null, DataProperty::MYSQL_INT)
        ];

        parent::__construct($initialValues);
    }

    protected string $databaseTable = 'student_completed_test_questions';
    protected string $formFieldPrefixName = 'student_completed_test_questions';
    protected array $primaryKeys = ['id'];
}