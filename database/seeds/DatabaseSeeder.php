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
        DB::statement("SET @@auto_increment_increment=1;");
        DB::statement("SET @@auto_increment_offset=1;");

        $this->call(UsersTableSeeder::class);
        $this->call(CoursesTableSeeder::class);
        $this->call(InstructorConfigTableSeeder::class);
        $this->call(SessionsTableSeeder::class);
        $this->call(FormTemplatesTableSeeder::class);
        $this->call(FormsTableSeeder::class);
        $this->call(QuestionsTableSeeder::class);
        $this->call(GroupsTableSeeder::class);
        $this->call(StudentCourseTableSeeder::class);
        $this->call(UserGroupTableSeeder::class);
//        $this->call(StudentSessionTableSeeder::class);
    }

    /**
     * Truncates and resets the AUTO_INCREMENT key of the table to 1
     * @param string $table target table
     * @return void
     */
    public static function refreshTable(string $table)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table($table)->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
