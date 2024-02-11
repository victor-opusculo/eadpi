<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AdminUsers extends AbstractMigration
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
        $table = $this->table('administrators');
        $table
        ->addColumn('full_name', 'string', [ 'limit' => 280, 'null' => false ])
        ->addColumn('email', 'string', [ 'limit' => 280, 'null' => false ])
        ->addColumn('password_hash', 'string', [ 'limit' => 280, 'null' => false ])
        ->addIndex('email', [ 'unique' => true ])
        ->create();

        $adminDr =
        [
            'id' => 1,
            'full_name' => 'Escola do Parlamento de Itapevi',
            'email' => 'escoladoparlamento@itapevi.sp.leg.br',
            'password_hash' => password_hash('Epi.158$%', PASSWORD_DEFAULT)
        ];

        $table->insert($adminDr)->saveData();
    }

    public function down(): void
    {
        $table = $this->table('administrators');
        $table->drop()->save();
    }
}
