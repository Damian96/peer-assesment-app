<?php

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Magyarjeti\LaravelLipsum\LipsumFacade as Lipsum;

class SessionsTableSeeder extends Seeder
{
    const MAX = 30;
    private $table = 'sessions';

    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        DatabaseSeeder::refreshTable($this->table, true);
        $instructors = array_column(User::getInstructors()->toArray(), 'id');
        $departments = ['CCP', 'CBE', 'CES', 'CPY'];
        $lipsum = Lipsum::short()->text(3);
        for($i=1; $i<=self::MAX; $i++) {
            $code = rand(1, count($departments)) . random_int((1000), (1000+$i+self::MAX));
            DB::table($this->table)->insert([
                'course_id' => rand(1, CoursesTableSeeder::MAX),
                'status' => '1',
                'instructions' => $lipsum,
                'deadline' => Carbon::now(config('app.timezone'))->addMonths(rand(1,5)),
            ]);
        }
    }
}
