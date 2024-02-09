<?php

namespace VictorOpusculo\Eadpi\Lib\Model\Courses;

use mysqli;
use VOpus\PhpOrm\DataEntity;
use VOpus\PhpOrm\DataProperty;
use VOpus\PhpOrm\SqlSelector;

class Course extends DataEntity
{
    public function __construct(?array $initialValues = null)
    {
        $this->properties = (object)
        [
            'id' => new DataProperty(null, fn() => null, DataProperty::MYSQL_INT),
            'name' => new DataProperty('txtName', fn() => 'Sem nome definido', DataProperty::MYSQL_STRING),
            'presentation_html' => new DataProperty('txtPresentationHtml', fn() => null, DataProperty::MYSQL_STRING),
            'cover_image_url' => new DataProperty('txtCoverImage', fn() => null, DataProperty::MYSQL_STRING),
            'hours' => new DataProperty('numHours', fn() => 0, DataProperty::MYSQL_DOUBLE),
            'certificate_text' => new DataProperty('txtCertificateText', fn() => null, DataProperty::MYSQL_STRING),
            'min_points_required_on_tests' => new DataProperty('numRequiredPoints', fn() => 0, DataProperty::MYSQL_INT),
            'is_visible' => new DataProperty('chkIsVisible', fn() => 0, DataProperty::MYSQL_INT)
        ];

        parent::__construct($initialValues);
    }

    protected string $databaseTable = 'courses';
    protected string $formFieldPrefixName = 'courses';
    protected array $primaryKeys = ['id'];
    
    public array $modules = [];

    public function fetchModules(mysqli $conn) : self
    {
        $getter = new Module([ 'course_id' => $this->properties->id->getValue()->unwrapOr(0) ]);
        $this->modules = $getter->getAllFromCourse($conn);
        return $this;
    }

    public function getQuestionsTotalCount(mysqli $conn) : int
    {
        $selector = (new SqlSelector)
        ->addSelectColumn('COUNT(test_questions.id)')
        ->setTable('course_tests')
        ->addJoin("LEFT JOIN test_questions ON test_questions.test_id = course_tests.id")
        ->addWhereClause("course_tests.course_id = ?")
        ->addValue('i', $this->properties->id->getValue()->unwrapOr(0));

        return (int)$selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE);
    }

    public function exists(mysqli $conn) : bool
    {
        $selector = (new SqlSelector)
        ->addSelectColumn('COUNT(*)')
        ->setTable($this->databaseTable)
        ->addWhereClause("{$this->getWhereQueryColumnName('id')} = ?")
        ->addValue('i', $this->properties->id->getValue()->unwrap());

        $exists = $selector->run($conn, SqlSelector::RETURN_FIRST_COLUMN_VALUE);
        return $exists > 0;
    }

    public function getAll(mysqli $conn) : array
    {
        $selector = $this->getGetSingleSqlSelector()
        ->clearValues()
        ->clearWhereClauses();

        $drs = $selector->run($conn, SqlSelector::RETURN_ALL_ASSOC);
        return array_map([ $this, 'newInstanceFromDataRow' ], $drs);
    }

    public function getInfos(mysqli $conn) : array
    {
        $selector = (new SqlSelector)
        ->addSelectColumn('courses.hours')
        ->addSelectColumn('COUNT(DISTINCT course_modules.id) AS modules')
        ->addSelectColumn('COUNT(DISTINCT course_lessons.id) AS lessons')
        ->addSelectColumn('COUNT(DISTINCT course_tests.id) as tests')
        ->addSelectColumn('COUNT(DISTINCT course_lesson_block.id) AS blocks')
        ->setTable($this->databaseTable)
        ->addJoin("LEFT JOIN course_modules ON course_modules.course_id = courses.id")
        ->addJoin("LEFT JOIN course_lessons ON course_lessons.module_id = course_modules.id")
        ->addJoin("LEFT JOIN course_tests ON course_tests.course_id = courses.id")
        ->addJoin("LEFT JOIN course_lesson_block ON course_lesson_block.lesson_id = course_lessons.id")
        ->addWhereClause("{$this->getWhereQueryColumnName('id')} = ?")
        ->addValue('i', $this->properties->id->getValue()->unwrapOr(0));

        $dr = $selector->run($conn, SqlSelector::RETURN_SINGLE_ASSOC);
        return $dr;
    }
}