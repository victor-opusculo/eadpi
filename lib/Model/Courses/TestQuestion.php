<?php

namespace VictorOpusculo\Eadpi\Lib\Model\Courses;

use mysqli;
use VOpus\PhpOrm\DataEntity;
use VOpus\PhpOrm\DataProperty;
use VOpus\PhpOrm\SqlSelector;

class TestQuestion extends DataEntity
{
    public function __construct(?array $initialValues = null)
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
        ->setOrderBy('id ASC');

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        return array_map([ $this, 'newInstanceFromDataRow' ], $drs);
    }

    public function getQuestionsTotalPoints(mysqli $conn, int $courseId) : int
    {
        $selector = (new SqlSelector)
        ->addSelectColumn("SUM(test_questions.points)")
        ->setTable($this->databaseTable)
        ->addJoin("INNER JOIN course_tests ON course_tests.id = test_questions.test_id")
        ->addWhereClause("course_tests.course_id = ?")
        ->addValue('i', $courseId);

        $sum = (int)$selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE);
        return $sum;
    }

    public function decodeOptions() : array
    {
        return json_decode($this->properties->options->getValue()->unwrapOr('[]'));
    }

    public function decodeCorrectAnswers() : array
    {
        return json_decode($this->properties->correct_answers->getValue()->unwrapOr('[]'));
    }
}