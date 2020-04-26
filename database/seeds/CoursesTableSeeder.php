<?php

use App\Course;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoursesTableSeeder extends Seeder
{
    const MAX = 10;
    private $table = 'courses';

    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        if (!strcmp(env('APP_ENV'), 'local')) {
            $instructors = array_column(User::whereInstructor('1')->where('admin', '=', '0')->get(['users.id'])->toArray(), 'id');
            if (empty($instructors)) {
                throw new Exception("No Instructors in `users` table." . "\n" . "aborting...");
            }

            DatabaseSeeder::refreshTable($this->table, true);

            $months = [1, 2, 3, 4, 10, 11, 12];
            $departments = ['CCP', 'CBE', 'CES', 'CPY'];
            $titles = [
                'Logic Programming', 'Information Security', 'Software Engineering', 'Network Computing', 'Individual Project',
                'Java Programming', 'Object Oriented Programming', 'Artificial Intelligence', 'Continuous Mathematical Foundations',
                'Intro to Mathematical Foundations', 'Intro to Java Programming', 'Object Oriented Programming II',
            ];
            $years = range(config('constants.date.start'), intval(date('Y')));
            $range = range(1000, 1000 + self::MAX);
            for ($i = 1; $i <= self::MAX; $i++) {
                $code = $departments[array_rand($departments, 1)] . array_pop($range);
                $time = Carbon::createFromDate($years[array_rand($years, 1)], $months[array_rand($months, 1)], rand(1, 15), config('app.timezone'))->timestamp;
                DB::table($this->table)->insert([
                    'user_id' => $instructors[array_rand($instructors, 1)],
                    'title' => $titles[array_rand($titles, 1)],
                    'code' => $code,
                    'ac_year' => Course::toAcademicYear($time),
                    'department' => $departments[array_rand($departments, 1)],
                ]);
            }
        } else if (!strcmp(env('APP_ENV'), 'testing')) {
            /* `wpesdb`.`courses` */
            $courses = array(
                array('id' => '1', 'user_id' => '1', 'title' => '2020-CCP2213-NetworkComputing', 'status' => '1', 'code' => 'CCP2213', 'description' => NULL, 'department' => 'CCP', 'ac_year' => 'SP-2020', 'created_at' => '2020-04-25 14:13:55', 'updated_at' => '2020-04-25 14:13:55')
            );
        }

        // Insert DUMMY row
        DB::table($this->table)->insert([
            'id' => Course::DUMMY_ID,
            'user_id' => 1,
            'code' => 'N/A',
            'status' => '0',
            'ac_year' => 'UN-' . date('Y'),
            'department' => 'N/A',
        ]);
    }
}
