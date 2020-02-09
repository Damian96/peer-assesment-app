<?php

use App\Form;
use Illuminate\Database\Seeder;

class FormsTableSeeder extends Seeder
{
    private $table = 'forms';

    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Throwable
     */
    public function run()
    {
        DatabaseSeeder::refreshTable($this->table, true);

        /* `peerassessDB`.`forms` */
        $forms = array(
            array('session_id' => '1111', 'title' => 'Personal Tutorial - Academic & Transferable Skills - Course Questionnaire', 'subtitle' => 'Please submit feedback regarding the course of Personal Tutorial: Academic & Transferable Skills, including feedback on course structure, content, and instructor.', 'footnote' => NULL, 'mark' => '0',),
            array('session_id' => '1111', 'title' => 'Example Peer Evaluation Groupwork Form #1', 'subtitle' => NULL, 'footnote' => NULL, 'mark' => '0')
        );

        foreach ($forms as $f) {
            $model = new Form($f);
            $model->saveOrFail();
        }
    }
}
