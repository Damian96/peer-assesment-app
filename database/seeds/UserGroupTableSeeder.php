<?php

use App\StudentGroup;
use Illuminate\Database\Seeder;

class UserGroupTableSeeder extends Seeder
{
    private $table = 'user_group';

    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Throwable
     */
    public function run()
    {
        DatabaseSeeder::refreshTable($this->table, true);

        /* `peerassessDB`.`user_group` */
        $user_group = array(
            array('user_id' => '3', 'group_id' => '1'),
            array('user_id' => '4', 'group_id' => '1'),
            array('user_id' => '5', 'group_id' => '1')
        );

        foreach ($user_group as $ug) {
            $model = new StudentGroup($ug);
            $model->saveOrFail();
        }
    }
}
