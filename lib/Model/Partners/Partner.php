<?php

namespace VictorOpusculo\Eadpi\Lib\Model\Partners;

use Exception;
use mysqli;
use VictorOpusculo\Eadpi\Lib\Helpers\System;
use VictorOpusculo\Eadpi\Lib\Model\Media\Media;
use VictorOpusculo\Eadpi\Lib\Model\Media\Upload\MediaUpload;
use VOpus\PhpOrm\DataEntity;
use VOpus\PhpOrm\DataProperty;
use VOpus\PhpOrm\SqlSelector;

final class Partner extends DataEntity
{
    public function __construct(?array $initialValues = null)
    {
        $this->properties = (object)
        [
            'id' => new DataProperty('id', fn() => null, DataProperty::MYSQL_INT),
            'name' => new DataProperty('name', fn() => '', DataProperty::MYSQL_STRING),
            'description' => new DataProperty('description', fn() => '', DataProperty::MYSQL_STRING),
            'begin' => new DataProperty('begin', fn() => null, DataProperty::MYSQL_STRING),
            'end' => new DataProperty('end', fn() => null, DataProperty::MYSQL_STRING),
            'certificate_bg_media_id' => new DataProperty('certificate_bg_media_id', fn() => '', DataProperty::MYSQL_INT)
        ];

        parent::__construct($initialValues);
    }

    protected string $databaseTable = 'partners';
    protected string $formFieldPrefixName = 'partners';
    protected array $primaryKeys = ['id'];

    public ?Media $certificateBg = null;

    public function getCount(mysqli $conn, string $searchKeywords) : int
    {
        $selector = (new SqlSelector)
        ->addSelectColumn('COUNT(*)')
        ->setTable($this->databaseTable);

        if (mb_strlen($searchKeywords) > 3)
        {
            $selector = $selector
            ->addWhereClause('MATCH (name, description) AGAINST (?)')
            ->addValue('s', $searchKeywords);
        }

        $count = (int)$selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE);
        return $count;
    }

    public function getMultiple(mysqli $conn, string $searchKeywords, string $orderBy, int $page, int $numResultsOnPage) : array
    {
        $selector = (new SqlSelector)
        ->addSelectColumn($this->getSelectQueryColumnName('id'))
        ->addSelectColumn($this->getSelectQueryColumnName('name'))
        ->addSelectColumn($this->getSelectQueryColumnName('description'))
        ->addSelectColumn($this->getSelectQueryColumnName('begin'))
        ->addSelectColumn($this->getSelectQueryColumnName('end'))
        ->setTable($this->databaseTable);

        if (mb_strlen($searchKeywords) > 3)
        {
            $selector = $selector
            ->addWhereClause('MATCH (name, description) AGAINST (?)')
            ->addValue('s', $searchKeywords);
        }

        $selector = $selector
        ->setOrderBy(match ($orderBy)
        {
            'name' => 'name ASC',
            'date' => 'begin ASC',
            'id' => 'id DESC',
            default => 'id DESC'
        });

        $calcPage = ($page - 1) * $numResultsOnPage;
        $selector = $selector
        ->setLimit('?,?')
        ->addValues('ii', [ $calcPage, $numResultsOnPage ]);

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        return array_map([ $this, 'newInstanceFromDataRow' ], $drs);
    }

    public function exists(mysqli $conn) : bool
    {
        $selector = (new SqlSelector)
        ->addSelectColumn("COUNT(*)")
        ->setTable($this->databaseTable)
        ->addWhereClause("{$this->getWhereQueryColumnName('id')} = ?")
        ->addValue('i', $this->properties->id->getValue()->unwrapOr(0));

        $count = (int)$selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE);
        return $count > 0;
    }

    public function fetchCertificateBgMedia(mysqli $conn) : self
    {
        if (!$this->properties->certificate_bg_media_id->getValue()->unwrapOr(false))
            return $this;

        $this->certificateBg = (new Media([ 'id' => $this->properties->certificate_bg_media_id->getValue()->unwrap() ]))->getSingle($conn);
        return $this;
    }
}