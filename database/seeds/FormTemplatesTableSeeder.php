<?php

use Illuminate\Database\Seeder;

class FormTemplatesTableSeeder extends Seeder
{
    private $table = 'form_templates';

    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Throwable
     */
    public function run()
    {
        DatabaseSeeder::refreshTable($this->table, true);

        /* `peerassessDB`.`form_template` */
        $forms = array(
            array('title' => 'Personal Tutorial - Academic & Transferable Skills - Course Questionnaire', 'subtitle' => 'Please submit feedback regarding the course of Personal Tutorial: Academic & Transferable Skills, including feedback on course structure, content, and instructor.', 'footnote' => NULL),
            array('title' => 'Example Peer Evaluation Groupwork Form #1', 'subtitle' => NULL, 'footnote' => NULL)
        );

        foreach ($forms as $f) {
            DB::table($this->table)->insert($f);
        }

    }
}
