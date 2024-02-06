<?php

namespace VictorOpusculo\Eadpi\Lib\Model\Students;

use mysqli;
use VictorOpusculo\Eadpi\Lib\Model\Courses\Course;
use VOpus\PhpOrm\DataEntity;
use VOpus\PhpOrm\DataProperty;
use VOpus\PhpOrm\Exceptions\DatabaseEntityNotFound;
use VOpus\PhpOrm\SqlSelector;

class Student extends DataEntity
{
    public function __construct(?array $initialValues)
    {
        $this->properties = (object)
        [
            'id' => new DataProperty(null, fn() => null, DataProperty::MYSQL_INT, false),
            'email' => new DataProperty('txtEmail', fn() => null, DataProperty::MYSQL_STRING, true),
            'full_name' => new DataProperty('txtName', fn() => null, DataProperty::MYSQL_STRING, true),
            'password_hash' => new DataProperty(null, fn() => null, DataProperty::MYSQL_STRING, false),
            'timezone' => new DataProperty(null, fn() => 'America/Sao_Paulo', DataProperty::MYSQL_STRING, false)
        ];

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
        ->addWhereClause("{$this->getWhereQueryColumnName('email')} = ? ")
        ->addValue('s', $this->properties->email->getValue()->unwrapOr("n@d"));

        $dr = $selector->run($conn, SqlSelector::RETURN_SINGLE_ASSOC);

        if (empty($dr))
            throw new DatabaseEntityNotFound("E-mail não localizado!", $this->databaseTable);
        else
            return $this->newInstanceFromDataRow($dr);
    }

    public function checkPassword(string $givenPassword) : bool
    {
        return password_verify($givenPassword, $this->properties->password_hash->getValue()->unwrapOr('***'));
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