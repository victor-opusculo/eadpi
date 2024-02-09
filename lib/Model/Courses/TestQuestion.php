<?php

namespace VictorOpusculo\Eadpi\Lib\Model\Courses;

use mysqli;
use VOpus\PhpOrm\DataEntity;
use VOpus\PhpOrm\DataProperty;
use VOpus\PhpOrm\SqlSelector;

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

    protected string $databaseTable = 'test_questions';
    protected string $formFieldPrefixName = 'test_questions';
    protected array $primaryKeys = ['id']; 

    public function getAllFromTest(mysqli $conn) : array
    {
        $selector = $this->getGetSingleSqlSelector()
        ->clearValues()
        ->clearWhereClauses()
        ->addWhereClause("{$this->getWhereQueryColumnName('test_id')} = ?")
        ->addValue('i', $this->properties->test_id->getValue()->unwrapOr(0))
        ->setOrderBy('id');

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        return array_map([ $this, 'newInstanceFromDataRow' ], $drs);
    }
}