<?php

use App\Course;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoursesTableSeeder extends Seeder
{
    const MAX = 3;
    private $table = 'courses';

    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        DatabaseSeeder::refreshTable($this->table);

        /* `wpesDB.`courses` */
        $courses = array(
            array('user_id' => '2', 'title' => '2020-CCP2213-Artificial Intelligence', 'status' => '1', 'code' => 'CCP2213', 'department' => 'CCP', 'ac_year' => 'FA-2020', 'created_at' => '2020-05-25 14:13:55', 'updated_at' => '2020-05-25 14:13:55'),
            array('user_id' => '2', 'title' => '2020-CCP2213-Network Computing', 'status' => '1', 'code' => 'CCP2211', 'department' => 'CCP', 'ac_year' => 'SP-2019', 'created_at' => '2019-05-25 14:13:55', 'updated_at' => '2019-05-25 14:13:55'),
            array('user_id' => '2', 'title' => '2020-CCP2213-Computer Systems Architecture', 'status' => '1', 'code' => 'CCP1214', 'department' => 'CCP', 'ac_year' => 'FA-2019', 'created_at' => '2019-09-25 14:13:55', 'updated_at' => '2019-09-25 14:13:55')
        );

        foreach ($courses as $c) {
            DB::table($this->table)->insert($c);
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
