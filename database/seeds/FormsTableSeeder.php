<?php

use App\Course;
use App\Session;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
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
        DatabaseSeeder::refreshTable($this->table);

        if (!strcmp(config('env.APP_ENV', 'local'), 'testing')) {

            $courses = Course::getCurrentYears()->get(['id'])->toArray();
            $session = Session::query()->whereIn('id', array_column($courses, 'id'))
                ->orderBy('id', 'ASC')->firstOrFail();
            $now = Carbon::now(config('app.timezone'));

            /* `wpesdb`.`forms` */
            $forms = array(
                array('id' => '1', 'session_id' => $session->id, 'title' => 'Feedback Session for Session#1', 'mark' => '0', 'created_at' => $now->format(config('constants.date.stamp')), 'updated_at' => $now->format(config('constants.date.stamp')))
            );

            DB::table($this->table)->insert($forms[0]);
        }
    }
}
