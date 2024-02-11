<?php

namespace VictorOpusculo\Eadpi\Lib\Model\Courses;

use mysqli;
use VOpus\PhpOrm\DataEntity;
use VOpus\PhpOrm\DataProperty;
use VOpus\PhpOrm\Exceptions\DatabaseEntityNotFound;
use VOpus\PhpOrm\SqlSelector;

class GeneratedCertificate extends DataEntity
{
    public function __construct(?array $initialValues)
    {
        $this->properties = (object)
        [
            'id' => new DataProperty(null, fn() => null, DataProperty::MYSQL_INT),
            'course_id' => new DataProperty(null, fn() => null, DataProperty::MYSQL_INT),
            'student_id' => new DataProperty(null, fn() => null, DataProperty::MYSQL_INT),
            'datetime' => new DataProperty(null, fn() => gmdate("Y-m-d H:i:s"), DataProperty::MYSQL_STRING)
        ];

        parent::__construct($initialValues);
    }

    protected string $databaseTable = 'generated_certificates';
    protected string $formFieldPrefixName = 'generated_certificates';
    protected array $primaryKeys = ['id'];

    public function existsByCourseAndStudent(mysqli $conn) : bool
    {
        $selector = (new SqlSelector)
        ->addSelectColumn("COUNT(*)")
        ->setTable($this->databaseTable)
        ->addWhereClause("{$this->getWhereQueryColumnName('course_id')} = ?")
        ->addWhereClause(" AND {$this->getWhereQueryColumnName('student_id')} = ?")
        ->addValue('i', $this->properties->course_id->getValue()->unwrap() )
        ->addValue('i', $this->properties->student_id->getValue()->unwrap() );

        $count = $selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE);
        return (int)$count > 0;
    }

    public function getByCourseAndStudent(mysqli $conn) : self
    {
        $selector = (new SqlSelector)
        ->addSelectColumn($this->getSelectQueryColumnName('id'))
        ->addSelectColumn($this->getSelectQueryColumnName('datetime'))
        ->setTable($this->databaseTable)
        ->addWhereClause("{$this->getWhereQueryColumnName('course_id')} = ?")
        ->addWhereClause(" AND {$this->getWhereQueryColumnName('student_id')} = ?")
        ->addValue('i', $this->properties->course_id->getValue()->unwrap() )
        ->addValue('i', $this->properties->student_id->getValue()->unwrap() );

        $dr = $selector->run($conn, SqlSelector::RETURN_SINGLE_ASSOC);
        return $this->newInstanceFromDataRow($dr);
    }

    public function getByIdAndDatetime(mysqli $conn) : self
    {
        $selector = (new SqlSelector)
        ->addSelectColumn($this->getSelectQueryColumnName('id'))
        ->addSelectColumn($this->getSelectQueryColumnName('course_id'))
        ->addSelectColumn($this->getSelectQueryColumnName('student_id'))
        ->addSelectColumn($this->getSelectQueryColumnName('datetime'))
        ->setTable($this->databaseTable)
        ->addWhereClause("{$this->getWhereQueryColumnName('id')} = ?")
        ->addWhereClause(" AND {$this->getWhereQueryColumnName('datetime')} = ?")
        ->addValue('i', $this->properties->id->getValue()->unwrap() )
        ->addValue('s', $this->properties->datetime->getValue()->unwrap() );

        $dr = $selector->run($conn, SqlSelector::RETURN_SINGLE_ASSOC);

        if (isset($dr))
            return $this->newInstanceFromDataRow($dr);
        else
            throw new DatabaseEntityNotFound('Certificado não localizado!', $this->databaseTable);
    }
}