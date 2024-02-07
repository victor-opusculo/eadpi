<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class FillTestsData extends AbstractMigration
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
        $file = fopen(__DIR__ . "/premade_data/TesteAlunos.csv", "r");
        fgetcsv($file, 1000, ";");

        $table = $this->table('student_completed_test_questions');

        $alreadyPassedTests = [];

        while ($row = fgetcsv($file, 1000, ";"))
        {
            $subsIdStmt = $this->query("SELECT * FROM student_subscriptions WHERE student_subscriptions.student_id = ? AND student_subscriptions.course_id = ?", [  (int)$row[1], 1 ]);
            $subsRow = $subsIdStmt->fetch(PDO::FETCH_ASSOC);

            if (!empty($subsRow['id']))
            {
                if (array_search([ $subsRow['id'], $row[4] ], $alreadyPassedTests) === false)
                    $table->insert(
                        [
                            'id' => $row[0],
                            'student_id' => $row[1],
                            'subscription_id' => $subsRow['id'],
                            'question_id' => $row[4],
                            'completed_at' => $row[8],
                            'answers' => $row[7],
                            'is_correct' => $row[6],
                            'attempts' => 1
                        ]
                    );

                $alreadyPassedTests[] = [ $subsRow['id'], $row[4] ];
            }
        }      
        $table->saveData(); 
        fclose($file); 
    }

    Public function down() : void
    {
        $table = $this->table('student_completed_test_questions');
        $table->truncate();
    }
}
