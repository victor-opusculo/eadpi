<?php
namespace VictorOpusculo\Eadpi\Lib\Model\Settings;

use mysqli;
use VOpus\PhpOrm\DataEntity;
use VOpus\PhpOrm\DataProperty;

class LgpdTermVersion extends DataEntity
{
    public function __construct($initialValues = null)
    {
        $this->properties = (object)
        [
            'name' => new DataProperty(null, fn() => 'DEFAULT_LGPD_TERM_VERSION', DataProperty::MYSQL_STRING),
            'value' => new DataProperty(null, fn() => '', DataProperty::MYSQL_STRING)
        ];
        parent::__construct($initialValues);
    }

    protected string $databaseTable = 'settings';
    protected string $formFieldPrefixName = 'lgpdTermVersion';
    protected array $primaryKeys = ['name'];

}