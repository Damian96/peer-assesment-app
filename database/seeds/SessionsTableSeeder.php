<?php

use App\Course;
use App\Session;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SessionsTableSeeder extends Seeder
{
    const MAX = 5;
    private $table = 'sessions';

    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Exception|Throwable
     */
    public function run()
    {
        DatabaseSeeder::refreshTable($this->table);

        foreach (Course::getCurrentYears()->get(['courses.*']) as $course) {
            $session = factory(Session::class)->make(['course_id' => $course->id]);
            $session->saveOrFail();
        }

        // Insert DUMMY session
        DB::table($this->table)->insert([
            'id' => Course::DUMMY_ID,
            'course_id' => Course::DUMMY_ID,
            'title' => 'N/A',
            'instructions' => 'N/A',
            'groups' => 2,
            'min_group_size' => 2,
            'max_group_size' => 3,
        ]);
    }
}
