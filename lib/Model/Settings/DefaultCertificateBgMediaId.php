<?php
namespace VictorOpusculo\Eadpi\Lib\Model\Settings;

use VOpus\PhpOrm\DataEntity;
use VOpus\PhpOrm\DataProperty;

class DefaultCertificateBgMediaId extends DataEntity
{
    public function __construct($initialValues = null)
    {
        $this->properties = (object)
        [
            'name' => new DataProperty(null, fn() => 'DEFAULT_CERTIFICATE_BG_MEDIA_ID', DataProperty::MYSQL_STRING),
            'value' => new DataProperty(null, fn() => '', DataProperty::MYSQL_STRING)
        ];
        parent::__construct($initialValues);

        $this->properties->name->resetValue();
    }

    protected string $databaseTable = 'settings';
    protected string $formFieldPrefixName = 'defaultCertBgMediaId';
    protected array $primaryKeys = ['name'];
    protected array $setPrimaryKeysValue = ['name'];
}