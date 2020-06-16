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
        DatabaseSeeder::refreshTable($this->table);

        if (!strcmp(config('env.APP_ENV', 'local'), 'testing')) {
            /* `wpesdb`.`questions` */
            $questions = array(
                array('form_id' => '1', 'title' => 'Was always well prepared for group meetings', 'data' => '{"type":"criterion","choices":null}', 'created_at' => '2020-05-25 12:19:17', 'updated_at' => '2020-05-25 12:19:39'),
                array('form_id' => '1', 'title' => 'Completed an equal share of work', 'data' => '{"type":"criterion","choices":null}', 'created_at' => '2020-05-25 12:19:17', 'updated_at' => '2020-05-25 12:19:39'),
                array('form_id' => '1', 'title' => 'Produced an equal share of work on time', 'data' => '{"type":"criterion","choices":null}', 'created_at' => '2020-05-25 12:19:17', 'updated_at' => '2020-05-25 12:19:39'),
                array('form_id' => '1', 'title' => 'Produced work of high quality', 'data' => '{"type":"criterion","choices":null}', 'created_at' => '2020-05-25 12:19:17', 'updated_at' => '2020-05-25 12:19:39'),
                array('form_id' => '1', 'title' => 'Was always present in group meetings', 'data' => '{"type":"criterion","choices":null}', 'created_at' => '2020-05-25 12:19:17', 'updated_at' => '2020-05-25 12:19:39'),
                array('form_id' => '1', 'title' => 'Took an active role on initiating ideas or actions', 'data' => '{"type":"criterion","choices":null}', 'created_at' => '2020-05-25 12:19:17', 'updated_at' => '2020-05-25 12:19:39'),
                array('form_id' => '1', 'title' => 'Demonstrated leadership skills', 'data' => '{"type":"criterion","choices":null}', 'created_at' => '2020-05-25 12:19:17', 'updated_at' => '2020-05-25 12:19:39'),
                array('form_id' => '1', 'title' => 'Was easy/pleasant to work with', 'data' => '{"type":"criterion","choices":null}', 'created_at' => '2020-05-25 12:19:17', 'updated_at' => '2020-05-25 12:19:39'),
                array('form_id' => '1', 'title' => 'Comments about your contribution (shown to other teammates)', 'data' => '{"type":"paragraph","choices":null}', 'created_at' => '2020-05-25 12:19:17', 'updated_at' => '2020-05-25 12:19:39'),
                array('form_id' => '1', 'title' => 'Comments about team dynamics (confidential and only shown to instructor)', 'data' => '{"type":"paragraph","choices":null}', 'created_at' => '2020-05-25 12:19:17', 'updated_at' => '2020-05-25 12:19:39')
            );

            foreach ($questions as $q) {
                $q['form_id'] = 1;
                $model = new Question($q);
                $model->saveOrFail();
            }
        }
    }
}
