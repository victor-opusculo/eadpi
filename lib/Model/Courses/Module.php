<?php

namespace VictorOpusculo\Eadpi\Lib\Model\Courses;

use VOpus\PhpOrm\DataEntity;
use VOpus\PhpOrm\DataProperty;

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
}