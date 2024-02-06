<?php

namespace VictorOpusculo\Eadpi\Lib\Model\Students;

use Exception;
use mysqli;
use VictorOpusculo\Eadpi\Lib\Model\Courses\Course;
use VOpus\PhpOrm\DataEntity;
use VOpus\PhpOrm\DataProperty;
use VOpus\PhpOrm\SqlSelector;

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

    public ?Course $course;

    public function getAllFromStudent(mysqli $conn) : array
    {
        $selector = $this->getGetSingleSqlSelector()
        ->clearValues()
        ->clearWhereClauses()
        ->addWhereClause($this->getWhereQueryColumnName('student_id') . ' = ?')
        ->addValue('i', $this->properties->student_id->getValue()->unwrapOr(0));

        $dr = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        return array_map([$this, 'newInstanceFromDataRow'], $dr ?? []);
    }

    public function fetchCourse(mysqli $conn) : self
    {
        try
        {
            $this->course = (new Course([ 'id' => $this->properties->course_id->getValue()->unwrapOr(0) ]))->getSingle($conn);
        }
        catch (Exception $e) {}
        return $this;
    }

    public function getCompletedTestQuestionsCountAndScoredPoints(mysqli $conn) : array
    {
        $getter = new CompletedTestQuestion(
            [ 
                'student_id' => $this->properties->student_id->getValue()->unwrapOr(0),
                'subscription_id' => $this->properties->id->getValue()->unwrapOr(0)
            ]
        );
        return $getter->getCountFromSubscriptionAndStudent($conn);
    }
}