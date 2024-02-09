<?php

namespace VictorOpusculo\Eadpi\Lib\Model\Courses;

use mysqli;
use VOpus\PhpOrm\DataEntity;
use VOpus\PhpOrm\DataProperty;
use VOpus\PhpOrm\Exceptions\DatabaseEntityNotFound;
use VOpus\PhpOrm\SqlSelector;

class Module extends DataEntity
{
    public function __construct(?array $initialValues)
    {
        $this->properties = (object)
        [
            'id' => new DataProperty(null, fn() => null, DataProperty::MYSQL_INT),
            'course_id' => new DataProperty('numCourseId', fn() => null, DataProperty::MYSQL_INT),
            'title' => new DataProperty('txtTitle', fn() => 'Módulo sem nome', DataProperty::MYSQL_STRING),
            'presentation_html' => new DataProperty('txtPresentationHtml', fn() => null, DataProperty::MYSQL_STRING),
        ];

        parent::__construct($initialValues);
    }

    protected string $databaseTable = 'course_modules';
    protected string $formFieldPrefixName = 'course_modules';
    protected array $primaryKeys = ['id']; 

    public array $lessons = [];

    public function getAllFromCourse(mysqli $conn) : array
    {
        $selector = $this->getGetSingleSqlSelector()
        ->clearValues()
        ->clearWhereClauses()
        ->addWhereClause("{$this->getWhereQueryColumnName('course_id')} = ?")
        ->addValue('i', $this->properties->course_id->getValue()->unwrapOr(0))
        ->setOrderBy('id');

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        return array_map([ $this, 'newInstanceFromDataRow'], $drs);
    }

    public function fetchLessons(mysqli $conn) : self
    {
        $getter = new Lesson([ 'module_id' => $this->properties->id->getValue()->unwrapOr(0) ]);
        $this->lessons = $getter->getAllFromModule($conn);
        return $this;
    }

    /*
    public function getSingleFromCourse(mysqli $conn) : self
    {
        $selector = $this->getGetSingleSqlSelector()
        ->clearValues()
        ->clearWhereClauses()
        ->addWhereClause("{$this->getWhereQueryColumnName('course_id')} = ?")
        ->addValue('i', $this->properties->course_id->getValue()->unwrapOr(0));

        $dr = $selector->run($conn, SqlSelector::RETURN_SINGLE_ASSOC);
        if (isset($dr))
            return $this->newInstanceFromDataRow($dr);
        else    
            throw new DatabaseEntityNotFound("Módulo não localizado.", $this->databaseTable);
    }*/
}