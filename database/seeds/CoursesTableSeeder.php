<?php

use App\User;
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
        $departments = ['CCP', 'CBE', 'CES', 'CPY'];
        $lipsum = Lipsum::short()->text(3);
        for($i=1; $i<=self::MAX; $i++) {
            $code = $departments[rand(0, count($departments)-1)] . random_int((1000), (1000+$i+self::MAX));
            DB::table($this->table)->insert([
                'user_id' => $instructors[rand(0, count($instructors)-1)],
                'title' => substr($lipsum, rand(0, 15), (31-$i)),
                'code' => $code,
                'department' => $departments[array_rand($departments, 1)],
                'description' => substr($lipsum, 0, (100-$i)),
            ]);
        }
    }
}
