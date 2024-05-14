<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class MediaAndPartnersTables extends AbstractMigration
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
        $media = $this->table('media');
        $media
        ->addColumn('name', 'string', [ 'limit' => 140 ])
        ->addColumn('description', 'text')
        ->addColumn('file_extension', 'string', [ 'limit' => 50 ])
        ->addIndex([ 'name', 'description' ], [ 'type' => 'fulltext' ])
        ->create();

        $partners = $this->table('partners');
        $partners
        ->addColumn('name', 'string', [ 'limit' => 280, 'null' => false ])
        ->addColumn('description', 'text')
        ->addColumn('begin', 'date', [ 'null' => false ])
        ->addColumn('end', 'date', [ 'null' => true ])
        ->addColumn('certificate_bg_media_id', 'integer', [ 'signed' => false, 'null' => true ])
        ->addForeignKey('certificate_bg_media_id', 'media', 'id', [ 'delete' => 'SET_NULL', 'update' => 'CASCADE' ])
        ->addIndex([ 'name', 'description' ], [ 'type' => 'fulltext' ])
        ->create();

        $students = $this->table('students');
        $students
        ->addColumn('partner_id', 'integer', [ 'null' => true, 'signed' => false, 'after' => 'timezone' ])
        ->addForeignKey('partner_id', 'partners', 'id', [ 'delete' => 'SET_NULL', 'update' => 'CASCADE' ])
        ->update();
    }
}
