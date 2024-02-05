<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use VictorOpusculo\Eadpi\Lib\Model\Database\Connection;

require_once __DIR__ . '/../../lib/Model/Database/Connection.php';

final class FillStudentsData extends AbstractMigration
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
        //$configs = parse_ini_file(__DIR__ . "/../../../eadpi_config.ini", true);		
        $cryptoKey = 'fa57fe325c6401bf3a32e3dcfd01c692c3c839be9ab9f61f';

        $file = fopen(__DIR__ . "/premade_data/CursoAlunos.csv", "r");
        fgetcsv($file, 1000, ";");
        while ($row = fgetcsv($file, 1000, ";"))
        {
            $this->execute("INSERT INTO students (id, full_name, email, password_hash, timezone) VALUES (?, AES_ENCRYPT(TRIM(?), '$cryptoKey'), AES_ENCRYPT(LOWER(TRIM(?)), '$cryptoKey'), ?, ?)", 
            [ $row[0], $row[1], $row[2], password_hash("eadpi@aluno", PASSWORD_DEFAULT), 'America/Sao_Paulo']);

            if (!empty($row[4]))
            {
                $this->execute("INSERT INTO student_subscriptions (student_id, course_id, datetime) VALUES (?, 1, ?)", [ $row[0], $row[4] ]);
            }
        }
        fclose($file);
    }

    public function down() : void
    {
        $this->execute("DELETE FROM student_subscriptions");
        $this->execute("DELETE FROM students");
    }
}
