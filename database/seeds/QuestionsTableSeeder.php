<?php

use Illuminate\Database\Seeder;

class QuestionsTableSeeder extends Seeder
{
    protected $table = 'questions';

    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Throwable
     */
    public function run()
    {
        DatabaseSeeder::refreshTable($this->table, true);

//            foreach ($questions as $q) {
//                $q['form_id'] = $i;
//                $model = new Question($q);
//                $model->saveOrFail();
//            }
    }
}
