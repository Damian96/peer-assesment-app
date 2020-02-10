<?php

use App\Question;
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

        /* `peerassessDB`.`questions` */
        $questions = array(
            array('title' => 'How much has this tutorial helped your overall academic career?', 'subtitle' => NULL, 'data' => '{"type":"linear-scale","max":"5","minlbl":"Very little","maxlbl":"Very much","choices":null}'),
            array('title' => 'How related are the tutorials to your current studying material?', 'subtitle' => NULL, 'data' => '{"type":"linear-scale","max":"5","minlbl":"Not at all","maxlbl":"Very related","choices":null}'),
            array('title' => 'How satisfied are you with the current way of application of your knowledge in class?', 'subtitle' => NULL, 'data' => '{"type":"linear-scale","max":"5","minlbl":"Not satisfied at all","maxlbl":"Very satisfied","choices":null}'),
            array('title' => 'Where should the student emphasize?', 'subtitle' => NULL, 'data' => '{"type":"multiple-choice","max":null,"minlbl":null,"maxlbl":null,"choices":["Vocational guidance","Ways of learning","Both","Other\\u2026"]}'),
            array('title' => 'Do you have any other suggestions, to improve this unit?', 'subtitle' => NULL, 'data' => '{"type":"paragraph","max":null,"minlbl":null,"maxlbl":null,"choices":null}'),
            array('title' => 'How much did your teammates contribute to the report?', 'subtitle' => NULL, 'data' => '{"type":"eval","max":null,"minlbl":null,"maxlbl":null,"choices":null}'),
            array('title' => 'How much did your teammates contribute to the code?', 'subtitle' => NULL, 'data' => '{"type":"eval","max":null,"minlbl":null,"maxlbl":null,"choices":null}'),
            array('title' => 'How would you rate the cooperation of your teammates?', 'subtitle' => NULL, 'data' => '{"type":"eval","max":null,"minlbl":null,"maxlbl":null,"choices":null}'),
            array('title' => 'How would you rate the teamwork skills of your teammates?', 'subtitle' => NULL, 'data' => '{"type":"eval","max":null,"minlbl":null,"maxlbl":null,"choices":null}')
        );

        for ($i = 1; $i <= 2; $i++) {
            foreach ($questions as $q) {
                $q['form_id'] = $i;
                $model = new Question($q);
                $model->saveOrFail();
            }
        }
    }
}
