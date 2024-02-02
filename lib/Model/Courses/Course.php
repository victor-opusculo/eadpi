<?php

namespace VictorOpusculo\Eadpi\Lib\Model\Courses;

use VOpus\PhpOrm\DataEntity;
use VOpus\PhpOrm\DataProperty;

class Course extends DataEntity
{
    public function __construct(?array $initialValues)
    {
        $this->properties = (object)
        [
            'id' => new DataProperty(null, fn() => null, DataProperty::MYSQL_INT),
            'name' => new DataProperty('txtName', fn() => 'Sem nome definido', DataProperty::MYSQL_STRING),
            'presentation_html' => new DataProperty('txtPresentationHtml', fn() => null, DataProperty::MYSQL_STRING),
            'cover_image_url' => new DataProperty('txtCoverImage', fn() => null, DataProperty::MYSQL_STRING),
            'hours' => new DataProperty('numHours', fn() => 0, DataProperty::MYSQL_DOUBLE),
            'certificate_text' => new DataProperty('txtCertificateText', fn() => null, DataProperty::MYSQL_STRING),
            'min_points_required_on_tests' => new DataProperty('numRequiredPoints', fn() => 0, DataProperty::MYSQL_INT),
            'is_visible' => new DataProperty('chkIsVisible', fn() => 0, DataProperty::MYSQL_INT)
        ];

        parent::__construct($initialValues);
    }

    protected string $databaseTable = 'courses';
    protected string $formFieldPrefixName = 'courses';
    protected array $primaryKeys = ['id']; 
}