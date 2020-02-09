<?php

use App\Group;
use Illuminate\Database\Seeder;

class GroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Throwable
     */
    public function run()
    {
        /* `peerassessDB`.`groups` */
        $groups = array(
            array('session_id' => '1', 'name' => 'GROUP#1-Session#1', 'mark' => '0'),
            array('session_id' => '1', 'name' => 'GROUP#2-Session#2', 'mark' => '0')
        );

        foreach ($groups as $g) {
            $model = new Group($g);
            $model->saveOrFail();
        }
    }
}
