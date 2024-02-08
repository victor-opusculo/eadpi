<?php
namespace VictorOpusculo\Eadpi\Lib\Model\Settings;

use VOpus\PhpOrm\DataEntity;
use VOpus\PhpOrm\DataProperty;

class LgpdTermText extends DataEntity
{
    public function __construct($initialValues = null)
    {
        $this->properties = (object)
        [
            'name' => new DataProperty(null, fn() => 'DEFAULT_LGPD_TERM_TEXT', DataProperty::MYSQL_STRING),
            'value' => new DataProperty(null, fn() => '', DataProperty::MYSQL_STRING)
        ];
        parent::__construct($initialValues);
    }

    protected string $databaseTable = 'settings';
    protected string $formFieldPrefixName = 'lgpdTermText';
    protected array $primaryKeys = ['name'];
}