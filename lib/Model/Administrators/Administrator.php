<?php
namespace VictorOpusculo\Eadpi\Lib\Model\Administrators;

use mysqli;
use VOpus\PhpOrm\DataEntity;
use VOpus\PhpOrm\DataProperty;
use VOpus\PhpOrm\Exceptions\DatabaseEntityNotFound;
use VOpus\PhpOrm\SqlSelector;

class Administrator extends DataEntity
{
    public function __construct(?array $initialValues = null)
    {
        $this->properties = (object)
        [
            'id' => new DataProperty(null, fn() => null, DataProperty::MYSQL_INT),
            'full_name' => new DataProperty('fullname', fn() => null, DataProperty::MYSQL_STRING),
            'email' => new DataProperty('email', fn() => null, DataProperty::MYSQL_STRING),
            'password_hash' => new DataProperty(null, fn() => null, DataProperty::MYSQL_STRING)
        ];

        parent::__construct($initialValues);
    }

    protected string $databaseTable = 'administrators';
    protected string $formFieldPrefixName = 'administrators';
    protected array $primaryKeys = ['id']; 

    public function getByEmail(mysqli $conn) : self
    {
        $selector = $this->getGetSingleSqlSelector()
        ->clearValues()
        ->clearWhereClauses()
        ->addWhereClause("{$this->getWhereQueryColumnName('email')} = ?")
        ->addValue('s', $this->properties->email->getValue()->unwrapOr(''));

        $dr = $selector->run($conn, SqlSelector::RETURN_SINGLE_ASSOC);
        if (isset($dr))
            return $this->newInstanceFromDataRow($dr);
        else
            throw new DatabaseEntityNotFound("Conta não localizada!", $this->databaseTable);
    }

    public function existsEmail(mysqli $conn) : bool
    {
        $selector = (new SqlSelector)
        ->addSelectColumn('COUNT(*)')
        ->setTable($this->databaseTable)
        ->addWhereClause("{$this->getWhereQueryColumnName('email')} = ?")
        ->addValue('s', $this->properties->email->getValue()->unwrapOr(''));

        $count = $selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE);
        return (int)$count > 0;
    }

    public function existsAnotherAdminWithEmail(mysqli $conn) : bool
    {
        $selector = (new SqlSelector)
        ->addSelectColumn('COUNT(*)')
        ->setTable($this->databaseTable)
        ->addWhereClause("{$this->getWhereQueryColumnName('email')} = ?")
        ->addWhereClause(" AND {$this->getWhereQueryColumnName('id')} != ?")
        ->addValue('s', $this->properties->email->getValue()->unwrapOr(''))
        ->addValue('i', $this->properties->id->getValue()->unwrapOr(''));

        $count = $selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE);
        return (int)$count > 0;
    }

    public function checkPassword(string $password) : bool
    {
        return password_verify($password, $this->properties->password_hash->getValue()->unwrapOr('***'));
    }

    public function hashPassword(string $newPassword) : self
    {
        $this->properties->password_hash->setValue(password_hash($newPassword, PASSWORD_DEFAULT));
        return $this;
    }
}