<?php

namespace VictorOpusculo\Eadpi\Lib\Model\Courses;

use VOpus\PhpOrm\DataEntity;
use VOpus\PhpOrm\DataProperty;

class TestQuestion extends DataEntity
{
    public function __construct(?array $initialValues)
    {
        $this->properties = (object)
        [
            'id' => new DataProperty(null, fn() => null, DataProperty::MYSQL_INT),
            'test_id' => new DataProperty('numTestId', fn() => null, DataProperty::MYSQL_INT),
            'title' => new DataProperty('txtTitle', fn() => null, DataProperty::MYSQL_STRING),
            'options' => new DataProperty('hidOptionsJson', fn() => '[]', DataProperty::MYSQL_STRING),
            'correct_answers' => new DataProperty('hidCorrectAnswersJson', fn() => '[]', DataProperty::MYSQL_STRING),
            'points' => new DataProperty('numPoints', fn() => 1, DataProperty::MYSQL_INT)
        ];

        parent::__construct($initialValues);
    }

    protected string $databaseTable = 'test_question';
    protected string $formFieldPrefixName = 'test_question';
    protected array $primaryKeys = ['id']; 
}