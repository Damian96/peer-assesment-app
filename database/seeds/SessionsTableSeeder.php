<?php

use App\Course;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Magyarjeti\LaravelLipsum\LipsumFacade as Lipsum;

class SessionsTableSeeder extends Seeder
{
    const MAX = 60;
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
        $courses = Course::getCurrentYears();
        $cids = array_column($courses->toArray(), 'id');
        $lipsum = Lipsum::short()->text(3);
        for($i=1; $i<=self::MAX; $i++) {
            $c = array_rand($cids, 1);
            DB::table($this->table)->insert([
                'course_id' => $cids[$c],
                'title' => $courses[$c]->code . '-Session',
                'status' => '1',
                'instructions' => substr($lipsum, -(rand(0, 50))),
                'deadline' => Carbon::now(config('app.timezone'))->addMonths(rand(1,5)),
            ]);
        }
    }
}
