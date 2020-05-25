<?php

use App\Course;
use App\Session;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FormsTableSeeder extends Seeder
{
    private $table = 'forms';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DatabaseSeeder::refreshTable($this->table, true);

        $courses = Course::whereAcYear('SP-2020')->get(['courses.id'])->toArray();
        $session = Session::query()->whereIn('id', array_column($courses, 'id'))
            ->orderBy('id', 'ASC')->firstOrFail();

        if (!strcmp(env('APP_ENV', 'local'), 'local')) {
            /* `wpesdb`.`forms` */
            $forms = array(
                array('id' => '1', 'session_id' => $session->id, 'title' => 'Feedback Session for Session#1', 'mark' => '0', 'created_at' => '2020-05-25 11:51:08', 'updated_at' => '2020-05-25 12:00:08')
            );

            DB::table($this->table)->insert($forms[0]);
        }
    }
}
