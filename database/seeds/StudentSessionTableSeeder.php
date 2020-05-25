<?php

use App\Course;
use App\Session;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentSessionTableSeeder extends Seeder
{
    private $table = 'student_session';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DatabaseSeeder::refreshTable($this->table, true);

        if (!strcmp(env('APP_ENV', 'local'), 'local')) {
            $students = User::whereInstructor('0')->where('admin', '=', '0')
                ->whereNotNull('email_verified_at')->where('id', '!=', Course::DUMMY_ID)
                ->get(['users.id'])->toArray();
            $students = array_column($students, 'id');
            $courses = Course::whereAcYear('SP-2020')->get(['courses.id'])->toArray();
            $session = Session::whereIn('id', array_column($courses, 'id'))->firstOrFail();

//            dd($students, $courses, $session);
            foreach ($students as $id) {
                DB::table($this->table)->insert([
                    'user_id' => $id,
                    'session_id' => $session->id
                ]);
            }
        }
    }
}
