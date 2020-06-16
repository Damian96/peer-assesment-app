<?php

use App\InstructorConfig;
use App\User;
use Illuminate\Database\Seeder;

class InstructorConfigTableSeeder extends Seeder
{
    private $table = 'instructor_configs';

    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Exception|Throwable
     */
    public function run()
    {
        DatabaseSeeder::refreshTable($this->table);

        $instructors = array_column(User::whereInstructor('1')->where('admin', '=', '0')->get(['users.id'])->toArray(), 'id');
        if (empty($instructors)) {
            throw new Exception("No Instructors in `users` table." . "\n" . "aborting...");
        }

        foreach ($instructors as $id) {
            $config = new InstructorConfig(['user_id' => $id]);
            $config->saveOrFail();
        }
    }
}
