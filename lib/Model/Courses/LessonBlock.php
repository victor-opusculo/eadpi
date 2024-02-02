<?php

namespace VictorOpusculo\Eadpi\Lib\Model\Courses;

use VOpus\PhpOrm\DataEntity;
use VOpus\PhpOrm\DataProperty;

class LessonBlock extends DataEntity
{
    public function __construct(?array $initialValues)
    {
        $this->properties = (object)
        [
            'id' => new DataProperty(null, fn() => null, DataProperty::MYSQL_INT),
            'lesson_id' => new DataProperty('numLessonId', fn() => null, DataProperty::MYSQL_INT),
            'title' => new DataProperty('txtTitle', fn() => 'Bloco sem nome', DataProperty::MYSQL_STRING),
            'presentation_html' => new DataProperty('txtPresentationHtml', fn() => null, DataProperty::MYSQL_STRING),
            'video_host' => new DataProperty('radVideoHost', fn() => null, DataProperty::MYSQL_STRING),
            'video_url' => new DataProperty('txtVideoUrl', fn() => null, DataProperty::MYSQL_STRING)
        ];

        parent::__construct($initialValues);
    }

    protected string $databaseTable = 'course_lesson_blocks';
    protected string $formFieldPrefixName = 'course_lesson_blocks';
    protected array $primaryKeys = ['id']; 
}