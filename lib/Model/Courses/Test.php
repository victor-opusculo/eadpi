<?php

namespace VictorOpusculo\Eadpi\Lib\Model\Courses;

use mysqli;
use VOpus\PhpOrm\DataEntity;
use VOpus\PhpOrm\DataProperty;
use VOpus\PhpOrm\SqlSelector;

class Test extends DataEntity
{
    public function __construct(?array $initialValues)
    {
        $this->properties = (object)
        [
            'id' => new DataProperty(null, fn() => null, DataProperty::MYSQL_INT),
            'course_id' => new DataProperty('numCourseId', fn() => null, DataProperty::MYSQL_INT),
            'linked_to_type' => new DataProperty('hidLinkedToType', fn() => 'lesson', DataProperty::MYSQL_STRING),
            'linked_to_id' => new DataProperty('hidLinkedToId', fn() => null, DataProperty::MYSQL_INT),
            'title' => new DataProperty('txtTitle', fn() => 'Teste sem nome', DataProperty::MYSQL_STRING),
            'presentation_html' => new DataProperty('txtPresentationHtml', fn() => null, DataProperty::MYSQL_STRING)
        ];

        parent::__construct($initialValues);
    }

    public array $questions = [];

    protected string $databaseTable = 'course_tests';
    protected string $formFieldPrefixName = 'course_tests';
    protected array $primaryKeys = ['id']; 

    public function fetchQuestions(mysqli $conn) : self
    {
        $this->questions = (new TestQuestion([ 'test_id' => $this->properties->id->getValue()->unwrapOr(0) ]))->getAllFromTest($conn);
        return $this;
    }

    public function getAllFromIdAndTypeLinked(mysqli $conn) : array
    {
        $selector = $this->getGetSingleSqlSelector()
        ->clearValues()
        ->clearWhereClauses()
        ->addWhereClause("{$this->getWhereQueryColumnName('linked_to_type')} = ? ")
        ->addWhereClause(" AND {$this->getWhereQueryColumnName('linked_to_id')} = ? ")
        ->addValue('s', $this->properties->linked_to_type->getValue()->unwrapOr(''))
        ->addValue('i', $this->properties->linked_to_id->getValue()->unwrapOr(0))
        ->setOrderBy('id');

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        return array_map([ $this, 'newInstanceFromDataRow' ], $drs);
    }
}