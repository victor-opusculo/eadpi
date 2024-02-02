<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class DatabaseInitialSchema extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $students = $this->table('students');
        $students
        ->addColumn('email', 'varbinary', [ 'limit' => 400, 'null' => false ])
        ->addColumn('full_name', 'varbinary', [ 'limit' => 400, 'null' => false ])
        ->addColumn('password_hash', 'string', [ 'limit' => 400, 'null' => false ])
        ->create();

        $courses = $this->table('courses');
        $courses
        ->addColumn('name', 'string', [ 'limit' => 280, 'null' => false ])
        ->addColumn('presentation_html', 'text')
        ->addColumn('cover_image_url', 'string', [ 'limit' => 280 ])
        ->addColumn('hours', 'decimal', [ 'null' => false ])
        ->addColumn('certificate_text', 'text')
        ->addColumn('min_points_required_on_tests', 'integer', [ 'null' => false ])
        ->addColumn('is_visible', 'boolean', [ 'default' => 1 ])
        ->addIndex('name', [ 'type' => 'fulltext' ])
        ->create();

        $courseModules = $this->table('course_modules');
        $courseModules
        ->addColumn('course_id', 'integer', [ 'signed' => false, 'null' => false ])
        ->addColumn('title', 'string', [ 'limit' => 280 ])
        ->addColumn('presentation_html', 'text')
        ->addForeignKey('course_id', 'courses', 'id', [ 'update' => 'CASCADE', 'delete' => 'CASCADE' ])
        ->create();

        $courseLessons = $this->table('course_lessons');
        $courseLessons
        ->addColumn('module_id', 'integer', [ 'signed' => false, 'null' => false ])
        ->addColumn('title', 'string', [ 'limit' => 280 ])
        ->addColumn('presentation_html', 'text')
        ->addForeignKey('module_id', 'course_modules', 'id', [ 'update' => 'CASCADE', 'delete' => 'CASCADE' ])
        ->create();

        $courseLessonBlocks = $this->table('course_lesson_block');
        $courseLessonBlocks
        ->addColumn('lesson_id', 'integer', [ 'signed' => false, 'null' => false ])
        ->addColumn('title', 'string', [ 'limit' => 280 ])
        ->addColumn('presentation_html', 'text')
        ->addColumn('video_host', 'string', [ 'limit' => 280, 'null' => true ])
        ->addColumn('video_url', 'string', [ 'limit' => 280, 'null' => true ])
        ->addForeignKey('lesson_id', 'course_lessons', 'id', [ 'update' => 'CASCADE', 'delete' => 'CASCADE' ])
        ->create();

        $courseTests = $this->table('course_tests');
        $courseTests
        ->addColumn('course_id', 'integer', [ 'signed' => false, 'null' => false ])
        ->addColumn('linked_to_type', 'string', [ 'limit' => 20, 'null' => false ])
        ->addColumn('linked_to_id', 'integer', [ 'signed' => false, 'null' => false ])
        ->addColumn('title', 'string', [ 'limit' => 280 ])
        ->addColumn('presentation_html', 'text')
        ->addIndex([ 'linked_to_type', 'linked_to_id' ], [ 'unique' => true ])
        ->create();

        $testQuestions = $this->table('test_questions');
        $testQuestions
        ->addColumn('test_id', 'integer', [ 'signed' => false, 'null' => false ])
        ->addColumn('title', 'text')
        ->addColumn('options', 'json', [ 'null' => false ])
        ->addColumn('correct_answers', 'json', [ 'null' => false ])
        ->addColumn('points', 'integer', [ 'null' => false, 'default' => 1 ])
        ->addForeignKey('test_id', 'course_tests', 'id', [ 'update' => 'CASCADE', 'delete' => 'CASCADE' ])
        ->create();

        $categories = $this->table('categories');
        $categories
        ->addColumn('title', 'string', [ 'null' => false, 'limit' => 280 ])
        ->addColumn('icon_url', 'string', [ 'limit' => 280 ])
        ->create();

        $coursesCategoriesJoin = $this->table('courses_categories_join', [ 'id' => false, 'primary_key' => [ 'course_id', 'category_id' ] ]);
        $coursesCategoriesJoin
        ->addColumn('course_id', 'integer', [ 'signed' => false, 'null' => false ])
        ->addColumn('category_id', 'integer', [ 'signed' => false, 'null' => false ])
        ->addForeignKey('course_id', 'courses', 'id', [ 'update' => 'CASCADE', 'delete' => 'CASCADE' ])
        ->addForeignKey('category_id', 'categories', 'id', [ 'update' => 'CASCADE', 'delete' => 'CASCADE' ])
        ->create();

        $studentSubscriptions = $this->table('student_subscriptions');
        $studentSubscriptions
        ->addColumn('student_id', 'integer', [ 'signed' => false, 'null' => true ])
        ->addColumn('course_id', 'integer', [ 'signed' => false, 'null' => true ])
        ->addColumn('datetime', 'datetime')
        ->addForeignKey('course_id', 'courses', 'id', [ 'update' => 'CASCADE', 'delete' => 'SET_NULL' ])
        ->addForeignKey('student_id', 'students', 'id', [ 'update' => 'CASCADE', 'delete' => 'SET_NULL' ])
        ->create();

        $studentCompletedTestQuestions = $this->table('student_completed_test_questions');
        $studentCompletedTestQuestions
        ->addColumn('student_id', 'integer', [ 'signed' => false, 'null' => false ])
        ->addColumn('subscription_id', 'integer', [ 'signed' => false, 'null' => false ])
        ->addColumn('question_id', 'integer', [ 'signed' => false, 'null' => false ])
        ->addColumn('completed_at', 'datetime', [ 'null' => false ])
        ->addColumn('answers', 'json', [ 'null' => false ])
        ->addColumn('is_correct', 'boolean')
        ->addColumn('attempts', 'integer')
        ->addForeignKey('student_id', 'students', 'id', [ 'update' => 'CASCADE', 'delete' => 'CASCADE' ])
        ->addForeignKey('subscription_id', 'student_subscriptions', 'id', [ 'update' => 'CASCADE', 'delete' => 'CASCADE' ])
        ->addForeignKey('question_id', 'test_questions', 'id', [ 'update' => 'CASCADE', 'delete' => 'CASCADE' ])
        ->create();

        $generatedCertificates = $this->table('generated_certificates');
        $generatedCertificates
        ->addColumn('course_id', 'integer', [ 'signed' => false, 'null' => true ])
        ->addColumn('student_id', 'integer', [ 'signed' => false, 'null' => true ])
        ->addColumn('datetime', 'datetime', [ 'null' => false ])
        ->addForeignKey('course_id', 'courses', 'id', [ 'update' => 'CASCADE', 'delete' => 'SET_NULL' ])
        ->addForeignKey('student_id', 'students', 'id', [ 'update' => 'CASCADE', 'delete' => 'SET_NULL' ])
        ->addIndex([ 'course_id', 'student_id' ], [ 'unique' => true ])
        ->create();
    }
}
