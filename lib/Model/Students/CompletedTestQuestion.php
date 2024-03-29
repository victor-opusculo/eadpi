<?php

namespace VictorOpusculo\Eadpi\Lib\Model\Students;

use DateTime;
use DateTimeZone;
use mysqli;
use VOpus\PhpOrm\DataEntity;
use VOpus\PhpOrm\DataProperty;
use VOpus\PhpOrm\SqlSelector;

class CompletedTestQuestion extends DataEntity
{
    public function __construct(?array $initialValues)
    {
        $this->properties = (object)
        [
            'id' => new DataProperty(null, fn() => null, DataProperty::MYSQL_INT),
            'student_id' => new DataProperty(null, fn() => null, DataProperty::MYSQL_INT),
            'subscription_id' => new DataProperty(null, fn() => null, DataProperty::MYSQL_INT),
            'question_id' => new DataProperty(null, fn() => null, DataProperty::MYSQL_INT),
            'completed_at' => new DataProperty(null, fn() => gmdate("Y-m-d H:i:s"), DataProperty::MYSQL_STRING),
            'answers' => new DataProperty(null, fn() => null, DataProperty::MYSQL_STRING),
            'is_correct' => new DataProperty(null, fn() => null, DataProperty::MYSQL_INT),
            'attempts' => new DataProperty(null, fn() => null, DataProperty::MYSQL_INT)
        ];

        parent::__construct($initialValues);
    }

    protected string $databaseTable = 'student_completed_test_questions';
    protected string $formFieldPrefixName = 'student_completed_test_questions';
    protected array $primaryKeys = ['id'];

    public function getAllFromSubscriptionAndStudent(mysqli $conn) : array
    {
        $selector = $this->getGetSingleSqlSelector()
        ->clearWhereClauses()
        ->clearValues()
        ->addWhereClause("{$this->getWhereQueryColumnName('student_id')} = ? ")
        ->addWhereClause("AND {$this->getWhereQueryColumnName('subscription_id')} = ? ")
        ->addValues('ii', [ $this->properties->student_id->getValue()->unwrapOr(0), $this->properties->subscription_id->getValue()->unwrapOr(0) ]);

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        return array_map([ $this, 'newInstanceFromDataRow' ], $drs);
    }

    public function getCountFromSubscriptionAndStudent(mysqli $conn) : array
    {
        $selector = (new SqlSelector)
        ->addSelectColumn('COUNT(*) AS count')
        ->addSelectColumn("SUM(IF({$this->databaseTable}.is_correct, test_questions.points, 0)) AS scored_points")
        ->addSelectColumn("SUM(test_questions.points) AS total_points")
        ->setTable($this->databaseTable)
        ->addJoin("LEFT JOIN test_questions ON test_questions.id = {$this->databaseTable}.question_id")
        ->addWhereClause("{$this->getWhereQueryColumnName('student_id')} = ? ")
        ->addWhereClause("AND {$this->getWhereQueryColumnName('subscription_id')} = ? ")
        ->addValues('ii', [ $this->properties->student_id->getValue()->unwrapOr(0), $this->properties->subscription_id->getValue()->unwrapOr(0) ]);

        $dr = $selector->run($conn, SqlSelector::RETURN_SINGLE_ASSOC);
        return [ (int)$dr['count'] ?? 0, (int)$dr["scored_points"] ?? 0, (int)$dr["total_points"] ?? 0 ];
    }

    public function getSingleFromQuestionIdAndSubscription(mysqli $conn) : self|null
    {
        $selector = $this->getGetSingleSqlSelector()
        ->clearValues()
        ->clearWhereClauses()
        ->addWhereClause("{$this->getWhereQueryColumnName('question_id')} = ?")
        ->addWhereClause(" AND {$this->getWhereQueryColumnName('subscription_id')} = ?")
        ->addValue('i', $this->properties->question_id->getValue()->unwrapOr(0))
        ->addValue('i', $this->properties->subscription_id->getValue()->unwrapOr(0))
        ->setOrderBy("completed_at DESC");

        $dr = $selector->run($conn, SqlSelector::RETURN_SINGLE_ASSOC);

        if (isset($dr))
            return $this->newInstanceFromDataRow($dr);
        else   
            return null;
    }

    public function getLastCompletedTestDatetimeFromSubscription(mysqli $conn) : DateTime
    {
        $selector = (new SqlSelector)
        ->addSelectColumn("MAX(completed_at) AS maxDateTime")
        ->setTable($this->databaseTable)
        ->addWhereClause("{$this->getWhereQueryColumnName('subscription_id')} = ?")
        ->addValue('i', $this->properties->subscription_id->getValue()->unwrapOr(0));

        $dt = $selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE);
        return new DateTime($dt, new DateTimeZone("UTC"));
    }

    public function registerAttempt(array $studentAnswers, array $correctAnswers) : self
    {
        $this->properties->attempts->setValue($this->properties->attempts->getValue()->unwrapOr(0) + 1);
        $this->properties->completed_at->setValue(gmdate('Y-m-d H:i:s'));
        $this->properties->answers->setValue(json_encode($studentAnswers));

        $allCorrect = true;
        foreach ($correctAnswers as $corr)
            $allCorrect = $allCorrect && (array_search($corr, $studentAnswers) !== false);

        $this->properties->is_correct->setValue($allCorrect ? 1 : 0);
        return $this;
    }
}