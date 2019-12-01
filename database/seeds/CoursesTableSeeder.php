<?php

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Magyarjeti\LaravelLipsum\LipsumFacade as Lipsum;

class CoursesTableSeeder extends Seeder
{
    const MAX = 30;
    private $table = 'courses';

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
        $months = [1, 2, 3, 9, 10, 11];
        $departments = ['CCP', 'CBE', 'CES', 'CPY'];
        $lipsum = Lipsum::short()->text(3);
        $years = range(config('constants.date.start'), intval(date('Y')));
        for($i=1; $i<=self::MAX; $i++) {
            $code = $departments[rand(0, count($departments)-1)] . random_int((1000), (1000+$i+self::MAX));
            DB::table($this->table)->insert([
                'user_id' => $instructors[array_rand($instructors, 1)],
                'title' => substr($lipsum, rand(1, 15), (50-$i)),
                'code' => $code,
                'ac_year' => Carbon::createFromDate($years[array_rand($years, 1)], $months[array_rand($months, 1)], 1, config('app.timezone'))->format(config('constants.date.stamp')),
                'department' => $departments[array_rand($departments, 1)],
                'description' => substr($lipsum, 0, (100-$i)),
            ]);
        }
    }
}
