<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(CoursesTableSeeder::class);
//        $this->call(SessionsTableSeeder::class);
        $this->call(FormTemplatesTableSeeder::class);
//        $this->call(GroupsTableSeeder::class);
//        $this->call(QuestionsTableSeeder::class);
        $this->call(StudentCourseTableSeeder::class);
//        $this->call(UserGroupTableSeeder::class);
    }

    /**
     * Truncates and resets the AUTO_INCREMENT key of the table to 1
     * @param string $table target table
     * @param bool $auto_inc whether to reset the AUTO_INCREMENT key
     * @return void
     */
    public static function refreshTable(string $table, $auto_inc = false)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table($table)->truncate();
        if ($auto_inc) {
            DB::statement("ALTER TABLE `$table` AUTO_INCREMENT = 1");
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
