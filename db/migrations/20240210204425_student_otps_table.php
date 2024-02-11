<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class StudentOtpsTable extends AbstractMigration
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
        $table = $this->table('student_otps');
        $table
        ->addColumn('student_id', 'integer', [ 'signed' => false, 'null' => false ])
        ->addColumn('otp', 'string', [ 'limit' => 280, 'null' => false ])
        ->addColumn('expiry_datetime', 'datetime', [ 'null' => false ])
        ->addForeignKey('student_id', 'students', [ 'id' ], [ 'delete' => 'CASCADE', 'update' => 'CASCADE' ])
        ->create();
    }
}
