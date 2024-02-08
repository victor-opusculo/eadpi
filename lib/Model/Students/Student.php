<?php

namespace VictorOpusculo\Eadpi\Lib\Model\Students;

use Exception;
use mysqli;
use VictorOpusculo\Eadpi\Lib\Model\Courses\Course;
use VOpus\PhpOrm\DataEntity;
use VOpus\PhpOrm\DataProperty;
use VOpus\PhpOrm\Exceptions\DatabaseEntityNotFound;
use VOpus\PhpOrm\Option;
use VOpus\PhpOrm\SqlSelector;

class Student extends DataEntity
{
    public function __construct(?array $initialValues = null)
    {
        $this->properties = (object)
        [
            'id' => new DataProperty(null, fn() => null, DataProperty::MYSQL_INT, false),
            'email' => new DataProperty('email', fn() => null, DataProperty::MYSQL_STRING, true),
            'full_name' => new DataProperty('fullName', fn() => null, DataProperty::MYSQL_STRING, true),
            'password_hash' => new DataProperty(null, fn() => null, DataProperty::MYSQL_STRING, false),
            'timezone' => new DataProperty('timeZone', fn() => 'America/Sao_Paulo', DataProperty::MYSQL_STRING, false),
            'lgpd_term_version' => new DataProperty('lgpdtermversion', fn() => null, DataProperty::MYSQL_INT),
            'lgpd_term' => new DataProperty('lgpdTermText', fn() => null, DataProperty::MYSQL_STRING)
        ];

        $this->properties->full_name->valueTransformer = 
            fn(Option $val) => Option::some(mb_convert_case($val->unwrapOrElse(fn() => throw new Exception('Nome não informado!')), MB_CASE_TITLE, "UTF-8"));

        parent::__construct($initialValues);
    }

    protected string $databaseTable = 'students';
    protected string $formFieldPrefixName = 'students';
    protected array $primaryKeys = ['id']; 

    public array $subscriptions = [];

    public function getByEmail(mysqli $conn) : self
    {
        $selector = $this->getGetSingleSqlSelector()
        ->clearWhereClauses()
        ->clearValues()
        ->addWhereClause("{$this->getWhereQueryColumnName('email')} = lower(?) ")
        ->addValue('s', $this->properties->email->getValue()->unwrapOr("n@d"));

        $dr = $selector->run($conn, SqlSelector::RETURN_SINGLE_ASSOC);

        if (empty($dr))
            throw new DatabaseEntityNotFound("E-mail não localizado!", $this->databaseTable);
        else
            return $this->newInstanceFromDataRow($dr);
    }

    public function existsEmail(mysqli $conn) : bool
    {
        $selector = (new SqlSelector)
        ->addSelectColumn('COUNT(*)')
        ->setTable($this->databaseTable)
        ->addWhereClause("{$this->getWhereQueryColumnName('email')} = lower(?) ")
        ->addValue('s', $this->properties->email->getValue()->unwrapOr(null));

        $count = $selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE);
        return (int)$count > 0;
    }

    public function checkPassword(string $givenPassword) : bool
    {
        return password_verify($givenPassword, $this->properties->password_hash->getValue()->unwrapOr('***'));
    }

    public function hashPassword(string $password) : self
    {
        $this->properties->password_hash->setValue(password_hash($password, PASSWORD_DEFAULT));
        return $this;
    }

    public function fetchSubscriptions(mysqli $conn) : self
    {
        $subsGetter = new Subscription([ 'student_id' => $this->properties->id->getValue()->unwrapOr(0) ]);
        $subscriptions = $subsGetter->getAllFromStudent($conn);
        
        foreach ($subscriptions as $sub)
            $sub->fetchCourse($conn);

        $this->subscriptions = $subscriptions;
        return $this;
    }
}