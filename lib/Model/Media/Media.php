<?php

namespace VictorOpusculo\Eadpi\Lib\Model\Media;

use Exception;
use mysqli;
use VictorOpusculo\Eadpi\Lib\Helpers\System;
use VictorOpusculo\Eadpi\Lib\Model\Media\Upload\MediaUpload;
use VOpus\PhpOrm\DataEntity;
use VOpus\PhpOrm\DataProperty;
use VOpus\PhpOrm\SqlSelector;

final class Media extends DataEntity
{
    public function __construct(?array $initialValues = null)
    {
        $this->properties = (object)
        [
            'id' => new DataProperty('id', fn() => null, DataProperty::MYSQL_INT),
            'name' => new DataProperty('name', fn() => '', DataProperty::MYSQL_STRING),
            'description' => new DataProperty('description', fn() => '', DataProperty::MYSQL_STRING),
            'file_extension' => new DataProperty('fileExtension', fn() => '', DataProperty::MYSQL_STRING)
        ];

        parent::__construct($initialValues);
    }

    protected string $databaseTable = 'media';
    protected string $formFieldPrefixName = 'media';
    protected string $fileUploadFieldName = 'mediaFile';
    protected array $primaryKeys = ['id'];

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
        ->addSelectColumn($this->getSelectQueryColumnName('file_extension'))
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
            'file_extension' => 'file_extension ASC',
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

    public function beforeDatabaseInsert(mysqli $conn): int
    {
        MediaUpload::checkForUploadError($this->postFiles, $this->fileUploadFieldName);
        $extension = MediaUpload::getExtension($this->postFiles, $this->fileUploadFieldName);
        $this->properties->file_extension->setValue($extension);
        return 0;
    }

    public function afterDatabaseInsert(mysqli $conn, $insertResult)
    {
        MediaUpload::uploadMediaFile($insertResult['newId'], $this->postFiles, $this->fileUploadFieldName);
        $insertResult['affectedRows']++;
        return $insertResult;
    }

    public function beforeDatabaseUpdate(mysqli $conn): int
    {
        if (!empty($this->postFiles[$this->fileUploadFieldName]))
        {
            MediaUpload::checkForUploadError($this->postFiles, $this->fileUploadFieldName);
            $extension = MediaUpload::getExtension($this->postFiles, $this->fileUploadFieldName);
            $this->properties->file_extension->setValue($extension);
        }
        return 0;
    }

    public function afterDatabaseUpdate(mysqli $conn, $updateResult)
    {
        if (!empty($this->postFiles[$this->fileUploadFieldName]))
        {
            $id = $this->properties->id->getValue()->unwrapOr(0);
            MediaUpload::deleteMediaFile($id);
            MediaUpload::uploadMediaFile($id, $this->postFiles, $this->fileUploadFieldName);
            $updateResult['affectedRows']++;
        }
        return $updateResult;
    }

    public function afterDatabaseDelete(mysqli $conn, $deleteResult)
    {
        $id = $this->properties->id->getValue()->unwrapOr(0);
        if (MediaUpload::deleteMediaFile($id))
            $deleteResult['affectedRows']++;

        return $deleteResult;
    }

    public function fullFileName() : string
    {
        $id = $this->properties->id->getValue()->unwrapOr(0);
        $ext = $this->properties->file_extension->getValue()->unwrapOr('');

        if ($id && $ext)
            return System::systemBaseDir() . "/uploads/media/$id.$ext";

        throw new Exception("Erro de entidade de mídia não carregada ao obter localização do arquivo.");
    }

    public function fileNameFromBaseDir() : string
    {
        $id = $this->properties->id->getValue()->unwrapOr(0);
        $ext = $this->properties->file_extension->getValue()->unwrapOr('');

        if ($id && $ext)
            return "uploads/media/$id.$ext";

        throw new Exception("Erro de entidade de mídia não carregada ao obter URL do arquivo.");
    }
}