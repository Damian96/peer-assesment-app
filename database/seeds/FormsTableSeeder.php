<?php

use Illuminate\Database\Seeder;

class FormsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DatabaseSeeder::refreshTable($this->table, true);
        if (env('APP_ENV', 'local') == 'testing') {

        }
    }
}
