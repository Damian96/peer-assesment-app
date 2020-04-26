<?php

use App\Course;
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

        $forms = array(
            array('title' => 'Example Feedback Session', 'subtitle' => NULL, 'footnote' => NULL, 'questions' => '"[{\\"title\\":\\"Was always well prepared for group meetings\\",\\"data\\":{\\"type\\":\\"criterion\\",\\"max\\":null,\\"minlbl\\":null,\\"maxlbl\\":null,\\"choices\\":null}},{\\"title\\":\\"Completed an equal share of work\\",\\"data\\":{\\"type\\":\\"criterion\\",\\"max\\":null,\\"minlbl\\":null,\\"maxlbl\\":null,\\"choices\\":null}},{\\"title\\":\\"Produced an equal share of work on time\\",\\"data\\":{\\"type\\":\\"criterion\\",\\"max\\":null,\\"minlbl\\":null,\\"maxlbl\\":null,\\"choices\\":null}},{\\"title\\":\\"Produced work of high quality\\",\\"data\\":{\\"type\\":\\"criterion\\",\\"max\\":null,\\"minlbl\\":null,\\"maxlbl\\":null,\\"choices\\":null}},{\\"title\\":\\"Was always present in group meetings\\",\\"data\\":{\\"type\\":\\"criterion\\",\\"max\\":null,\\"minlbl\\":null,\\"maxlbl\\":null,\\"choices\\":null}},{\\"title\\":\\"Took an active role on initiating ideas or actions\\",\\"data\\":{\\"type\\":\\"criterion\\",\\"max\\":null,\\"minlbl\\":null,\\"maxlbl\\":null,\\"choices\\":null}},{\\"title\\":\\"Demonstrated leadership skills\\",\\"data\\":{\\"type\\":\\"criterion\\",\\"max\\":null,\\"minlbl\\":null,\\"maxlbl\\":null,\\"choices\\":null}},{\\"title\\":\\"Was easy\\\\\\/pleasant to work with\\",\\"data\\":{\\"type\\":\\"criterion\\",\\"max\\":null,\\"minlbl\\":null,\\"maxlbl\\":null,\\"choices\\":null}},{\\"title\\":\\"Comments about your contribution (shown to other teammates)\\",\\"data\\":{\\"type\\":\\"paragraph\\",\\"max\\":null,\\"minlbl\\":null,\\"maxlbl\\":null,\\"choices\\":null}},{\\"title\\":\\"Comments about team dynamics (confidential and only shown to instructor)\\",\\"data\\":{\\"type\\":\\"paragraph\\",\\"max\\":null,\\"minlbl\\":null,\\"maxlbl\\":null,\\"choices\\":null}}]"', 'created_at' => '2020-03-25 21:46:16', 'updated_at' => '2020-03-25 21:46:16'),
            array('title' => 'Course Questionnaire', 'subtitle' => 'Please submit feedback regarding the course ', 'footnote' => NULL, 'questions' => '"[{\\"title\\": \\"How much has this course helped your overall academic career?\\",\\"data\\": {\\"type\\": \\"linear-scale\\",\\"max\\": 5,\\"choices\\": null}},{\\"title\\": \\"Where should the student emphasize?\\",\\"data\\": {\\"type\\": \\"multiple-choice\\",\\"max\\": null,\\"choices\\": [\\"Vocational Guidance\\",\\"Ways of learning\\",\\"Both\\"]}},{\\"title\\": \\"Should the lessons be more interactive?\\",\\"data\\": {\\"type\\": \\"multiple-choice\\",\\"max\\": null,\\"choices\\": [\\"Yes\\",\\"No\\",\\"It is okay as it is now\\"]}},{\\"title\\": \\"Do you want professionals guests in your tutorials?\\",\\"data\\": {\\"type\\": \\"multiple-choice\\",\\"max\\": null,\\"choices\\": [\\"No\\",\\"Yes\\",\\"N/A\\"]}},{\\"title\\": \\"Is the pace of the lesson too fast?\\",\\"data\\": {\\"type\\": \\"linear-scale\\",\\"max\\": 5,\\"choices\\": null}},{\\"title\\": \\"Do you have any other suggestions, to improve this unit?\\",\\"data\\": {\\"type\\": \\"paragraph\\",\\"max\\": null,\\"choices\\": null}}]"', 'created_at' => '2020-03-25 21:46:16', 'updated_at' => '2020-03-25 21:46:16')
        );

        foreach ($forms as $f) {
            $template = new \App\FormTemplate($f);
            $template->saveOrFail();
        }

    }
}
