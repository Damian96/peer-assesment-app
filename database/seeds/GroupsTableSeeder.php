<?php

use App\Group;
use Illuminate\Database\Seeder;

class GroupsTableSeeder extends Seeder
{
    const MAX = 2;
    private $table = 'groups';

    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Throwable
     * @FIXME: https://gist.githubusercontent.com/Damian96/c2f593224525915d88e0ae829cd662a6/raw/f8dfc02e83064c022255f821167e6823f656dd2d/sql.error
     */
    public function run()
    {
        DatabaseSeeder::refreshTable($this->table, true);
        
        /* `peerassessDB`.`groups` */
        $groups = array(
            array('session_id' => 1, 'name' => 'GROUP#1-Session#1', 'mark' => 0),
        );

        foreach ($groups as $g) {
            $model = new Group($g);
            $model->saveOrFail();
        }
    }
}
