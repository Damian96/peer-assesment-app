<?php

use App\StudentGroup;
use Illuminate\Database\Seeder;

class UserGroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Throwable
     */
    public function run()
    {
        /* `peerassessDB`.`user_group` */
        $user_group = array(
            array('user_id' => '4', 'group_id' => '1'),
            array('user_id' => '5', 'group_id' => '1'),
            array('user_id' => '6', 'group_id' => '1')
        );

        foreach ($user_group as $ug) {
            $model = new StudentGroup($ug);
            $model->saveOrFail();
        }
    }
}
