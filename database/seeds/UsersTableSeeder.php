<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    const MAX = 30;
    private $table = 'users';

    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     * @throws Throwable
     */
    public function run()
    {
        DatabaseSeeder::refreshTable($this->table, true);

        if (env('APP_ENV', 'local') == 'testing') {
            /* `wpesdb`.`users` */
            $users = array(
                array('id' => '1','email' => 'kdimopoulos@citycollege.sheffield.eu','email_verified_at' => '2020-04-25 23:59:00','fname' => 'Konstantinos','lname' => 'Dimopoulos','department' => 'CS','reg_num' => NULL,'instructor' => '1','admin' => '0','password' => '$2y$10$gzOhhWIwIluFaQwh/2GyTu3o5ybdCXNDlrEDpLubciyzaZPL7SR4u','remember_token' => 'asegviQzPzFMqoJlgTQHQGWCfDh2fzDk8qAtPMInfPXfJHGKRj9BZf1XpFl8','api_token' => NULL,'created_at' => '2020-04-25 14:12:42','updated_at' => '2020-04-25 14:13:01','last_login' => '2020-04-25 14:13:01'),
                array('id' => '2','email' => 'tcharatsidis@citycollege.sheffield.eu','email_verified_at' => NULL,'fname' => 'Thomas','lname' => 'Charatsidis','department' => 'CCP','reg_num' => 'CS17011','instructor' => '0','admin' => '0','password' => '','remember_token' => NULL,'api_token' => NULL,'created_at' => '2020-04-25 14:18:47','updated_at' => '2020-04-25 14:18:47','last_login' => '2020-04-25 14:18:47'),
                array('id' => '3','email' => 'pgrammatikopoulos@citycollege.sheffield.eu','email_verified_at' => NULL,'fname' => 'Petros','lname' => 'Grammatikopoulos','department' => 'CCP','reg_num' => 'CS17012','instructor' => '0','admin' => '0','password' => '','remember_token' => NULL,'api_token' => NULL,'created_at' => '2020-04-25 14:18:47','updated_at' => '2020-04-25 14:18:47','last_login' => '2020-04-25 14:18:47'),
                array('id' => '4','email' => 'skimisoglou@citycollege.sheffield.eu','email_verified_at' => NULL,'fname' => 'Stefania','lname' => 'Kimisoglou','department' => 'CCP','reg_num' => 'CS17013','instructor' => '0','admin' => '0','password' => '','remember_token' => NULL,'api_token' => NULL,'created_at' => '2020-04-25 14:18:47','updated_at' => '2020-04-25 14:18:47','last_login' => '2020-04-25 14:18:47'),
                array('id' => '5','email' => 'kmedovshchikov@citycollege.sheffield.eu','email_verified_at' => NULL,'fname' => 'Kirill','lname' => 'Medovshchikov','department' => 'CCP','reg_num' => 'CS17014','instructor' => '0','admin' => '0','password' => '','remember_token' => NULL,'api_token' => NULL,'created_at' => '2020-04-25 14:18:47','updated_at' => '2020-04-25 14:18:47','last_login' => '2020-04-25 14:18:47'),
                array('id' => '6','email' => 'apezodromou@citycollege.sheffield.eu','email_verified_at' => NULL,'fname' => 'Apostolos','lname' => 'Pezodromou','department' => 'CCP','reg_num' => 'CS17015','instructor' => '0','admin' => '0','password' => '','remember_token' => NULL,'api_token' => NULL,'created_at' => '2020-04-25 14:18:47','updated_at' => '2020-04-25 14:18:47','last_login' => '2020-04-25 14:18:47'),
                array('id' => '7','email' => 'ctasioudis@citycollege.sheffield.eu','email_verified_at' => NULL,'fname' => 'Christos','lname' => 'Tasioudis','department' => 'CCP','reg_num' => 'CS17016','instructor' => '0','admin' => '0','password' => '','remember_token' => NULL,'api_token' => NULL,'created_at' => '2020-04-25 14:18:47','updated_at' => '2020-04-25 14:18:47','last_login' => '2020-04-25 14:18:47')
            );
        } else {
            /* `peerassessDB`.`users` */
            $users = array(
                array('email' => 'dgiankakis@citycollege.sheffield.eu', 'email_verified_at' => '2020-01-05 14:06:22', 'fname' => 'Damianos', 'lname' => 'Giankakis', 'department' => 'CS', 'reg_num' => NULL, 'instructor' => '1', 'admin' => '1', 'password' => '$2y$10$aRhprjpSXD5aqQJfi0j/Je09chffkKNKBuAU3ZvsE3OsD31sSjj7a', 'remember_token' => 'PfVsnllUpVYfsPc8FsBh0ARyUjJhJiP63gApa8V3Vs3vSElIPLIeyk8JTGGg', 'api_token' => '15ec9f8582faca5ca4fca96cc9e03f36e5bdee37722a4c03bc2e6d0c8f70b8e5'),
                array('email' => 'istamatopoulou@citycollege.sheffield.eu', 'email_verified_at' => '2020-01-05 14:29:47', 'fname' => 'Ioanna', 'lname' => 'Stamatopoulou', 'department' => 'CS', 'reg_num' => NULL, 'instructor' => '1', 'admin' => '0', 'password' => '$2y$10$gzOhhWIwIluFaQwh/2GyTu3o5ybdCXNDlrEDpLubciyzaZPL7SR4u', 'remember_token' => 'Q1fwcrJkOGX1CUdiHKc514x54Xz3a2dhxN8d31Jq5F3LAYC2NqyBVdhk0XTT'),
                array('email' => 'pgrammatikopoulos@citycollege.sheffield.eu', 'email_verified_at' => '2020-01-13 11:28:23', 'fname' => 'Petros', 'lname' => 'Grammatikopoulos', 'department' => 'CS', 'reg_num' => 'CS17020', 'instructor' => '0', 'admin' => '0', 'password' => '$2y$10$IfDFpctQS6PtZP2YucTVsuSUwNY3Pi1vbc7/nSiIFWJJtHY/PrXby', 'remember_token' => 'OC9duIW1zmPoDCAvRMMmFcVSyaGJ4YQZwvjNxOAfmgooNDS5op2YN3OxCt9M'),
                array('email' => 'ezafiriadou@citycollege.sheffield.eu', 'email_verified_at' => '2020-02-08 00:00:00', 'fname' => 'Eleni', 'lname' => 'Zafiriadou', 'department' => 'CS', 'reg_num' => 'CS17021', 'instructor' => '0', 'admin' => '0', 'password' => '$2y$10$IfDFpctQS6PtZP2YucTVsuSUwNY3Pi1vbc7/nSiIFWJJtHY/PrXby'),
                array('email' => 'kdimitropoulos@citycollege.sheffield.eu', 'email_verified_at' => '2020-02-08 00:00:00', 'fname' => 'Konstantinos', 'lname' => 'Dimitropoulos', 'department' => 'CS', 'reg_num' => 'CS17021', 'instructor' => '0', 'admin' => '0', 'password' => '$2y$10$IfDFpctQS6PtZP2YucTVsuSUwNY3Pi1vbc7/nSiIFWJJtHY/PrXby'),
                array('id' => \App\Course::DUMMY_ID, 'email' => 'dummy@citycollege.sheffield.eu', 'email_verified_at' => '2020-02-08 00:00:00', 'fname' => 'Joe', 'lname' => 'Doe', 'department' => 'CS', 'reg_num' => 'CS17021', 'instructor' => '0', 'admin' => '0', 'password' => '$2y$10$Cx1z1/2SPElYQ49gKxXUw.c5GOknsBVl8tjbDsL4j7cgYZ0a.A/gG')
            );
        }

        foreach ($users as $u) {
            $user = new User($u);
            $user->saveOrFail();
        }
    }
}
