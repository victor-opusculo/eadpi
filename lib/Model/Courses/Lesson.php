<?php

namespace VictorOpusculo\Eadpi\Lib\Model\Courses;

use VOpus\PhpOrm\DataEntity;
use VOpus\PhpOrm\DataProperty;

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
}