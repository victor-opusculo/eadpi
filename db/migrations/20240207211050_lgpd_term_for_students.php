<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class LgpdTermForStudents extends AbstractMigration
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
    public function up(): void
    {
        $table = $this->table('settings', [ 'id' => false, 'primary_key' => 'name' ]);
        $table
        ->addColumn('name', 'string', [ 'limit' => 140, 'null' => false ])
        ->addColumn('value', 'text', [ 'null' => false ])
        ->create();

        $table->insert([ 'name' => 'DEFAULT_LGPD_TERM_VERSION', 'value' => '1' ]);
        $table->insert([ 'name' => 'DEFAULT_LGPD_TERM_TEXT', 'value' => file_get_contents(__DIR__ . '/premade_data/lgpdTerm1.html') ]);
        $table->saveData();

        $table = $this->table('students');
        $table
        ->addColumn('lgpd_term_version', 'integer')
        ->addColumn('lgpd_term', 'text')
        ->update();
    }

    public function down(): void
    {
        $table = $this->table('settings');
        $table
        ->drop()
        ->save();

        $table = $this->table('students');
        $table
        ->removeColumn('lgpdTerm')
        ->removeColumn('lgpdTermVersion')
        ->update();

    }
}
