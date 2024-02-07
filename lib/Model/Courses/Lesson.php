<?php

namespace VictorOpusculo\Eadpi\Lib\Model\Courses;

use mysqli;
use VOpus\PhpOrm\DataEntity;
use VOpus\PhpOrm\DataProperty;
use VOpus\PhpOrm\SqlSelector;

class Lesson extends DataEntity
{
    public function __construct(?array $initialValues)
    {
        $this->properties = (object)
        [
            'id' => new DataProperty(null, fn() => null, DataProperty::MYSQL_INT),
            'module_id' => new DataProperty('numModuleId', fn() => null, DataProperty::MYSQL_INT),
            'title' => new DataProperty('txtTitle', fn() => 'Aula sem nome', DataProperty::MYSQL_STRING),
            'presentation_html' => new DataProperty('txtPresentationHtml', fn() => null, DataProperty::MYSQL_STRING),
        ];

        parent::__construct($initialValues);
    }

    protected string $databaseTable = 'course_lessons';
    protected string $formFieldPrefixName = 'course_lessons';
    protected array $primaryKeys = ['id']; 

    public array $blocks = [];

    public function fetchBlocks(mysqli $conn) : self
    {
        $this->blocks = (new LessonBlock([ 'lesson_id' => $this->properties->id->getValue()->unwrapOr(0) ]))->getAllFromLesson($conn);
        return $this;
    }

    public function getAllFromModule(mysqli $conn) : array
    {
        $selector = $this->getGetSingleSqlSelector()
        ->clearValues()
        ->clearWhereClauses()
        ->addWhereClause("{$this->getWhereQueryColumnName('module_id')} = ?")
        ->addValue('i', $this->properties->module_id->getValue()->unwrapOr(0))
        ->setOrderBy('id');

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        return array_map([ $this, 'newInstanceFromDataRow'], $drs);
    }
}