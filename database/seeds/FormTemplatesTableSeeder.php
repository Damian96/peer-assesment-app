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
            array('title' => 'Example Feedback Session', 'questions' => '[{"title":"Was always well prepared for group meetings","data":{"type":"criteria","max":null,"minlbl":null,"maxlbl":null,"choices":null}},{"title":"Completed an equal share of work","data":{"type":"criteria","max":null,"minlbl":null,"maxlbl":null,"choices":null}},{"title":"Produced an equal share of work on time","data":{"type":"criteria","max":null,"minlbl":null,"maxlbl":null,"choices":null}},{"title":"Produced work of high quality","data":{"type":"criteria","max":null,"minlbl":null,"maxlbl":null,"choices":null}},{"title":"Was always present in group meetings","data":{"type":"criteria","max":null,"minlbl":null,"maxlbl":null,"choices":null}},{"title":"Took an active role on initiating ideas or actions","data":{"type":"criteria","max":null,"minlbl":null,"maxlbl":null,"choices":null}},{"title":"Demonstrated leadership skills","data":{"type":"criteria","max":null,"minlbl":null,"maxlbl":null,"choices":null}},{"title":"Was easy\/pleasant to work with","data":{"type":"criteria","max":null,"minlbl":null,"maxlbl":null,"choices":null}},{"title":"Comments about your contribution (shown to other teammates)","data":{"type":"paragraph","max":null,"minlbl":null,"maxlbl":null,"choices":null}},{"title":"Comments about team dynamics (confidential and only shown to instructor)","data":{"type":"paragraph","max":null,"minlbl":null,"maxlbl":null,"choices":null}}]', 'user_id' => 1111),
        );

        foreach ($forms as $f) {
            $template = new \App\FormTemplate($f);
            $template->saveOrFail();
        }

    }
}
