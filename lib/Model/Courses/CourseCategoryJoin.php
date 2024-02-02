<?php

namespace VictorOpusculo\Eadpi\Lib\Model\Courses;

use VOpus\PhpOrm\DataEntity;
use VOpus\PhpOrm\DataProperty;

class CourseCategoryJoin extends DataEntity
{
    public function __construct(?array $initialValues)
    {
        $this->properties = (object)
        [
            'course_id' => new DataProperty(null, fn() => null, DataProperty::MYSQL_INT),
            'category_id' => new DataProperty(null, fn() => null, DataProperty::MYSQL_INT)
        ];

        parent::__construct($initialValues);
    }

    protected string $databaseTable = 'courses_categories_join';
    protected string $formFieldPrefixName = 'courses_categories_join';
    protected array $primaryKeys = ['course_id', 'category_id']; 
    protected array $setPrimaryKeysValue = [ 'course_id', 'category_id' ];
}