<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    const MAX = 30;
    private $table = 'users';

    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        DatabaseSeeder::refreshTable($this->table, true);
        $departments = ['CCP', 'CBE', 'CES', 'CPY'];
        $codes = ['CS', 'CB', 'CE', 'CP'];
        $date = date('Y/m/d H:i:s');
        $names = [
            "Talisha Beringer",
            "Olimpia Mellinger",
            "Francisco Woolery",
            "Margart Bowden",
            "Luise Letcher",
            "Latonia Collett",
            "Paulina Gallop",
            "Lynetta Gargano",
            "Danika Schlagel",
            "Leoma Vanderveen",
            "Jerrie Fawley",
            "Kelsi Rozzell",
            "Shantelle Rencher",
            "Monet Batalla",
            "Lurlene Strandberg",
            "Shonta Arvizo",
            "Buck Peachey",
            "Lorrine Cashwell",
            "Mercy Cedano",
            "Theola Kube",
            "Ernie Shain",
            "Shanna Kuehl",
            "Delois Kearse",
            "Sophie Creviston",
            "Reyna Pursley",
            "Ahmed Cleary",
            "Rueben Kula",
            "Bruce Copeland",
            "Johnson Schlottmann",
            "Gaylene Prentis"
        ];

        for ($i = 1; $i <= self::MAX; $i++) {
            $rand = mt_rand(1000, 2000);
            $full_name = explode(' ', array_pop($names));
            $password = rand(100, 200) . substr($full_name[1], -3) . rand(200, 300);
            DB::table($this->table)->insert([
                'email' => substr($full_name[0], 0, 1) . $full_name[1] . '@citycollege.sheffield.eu',
                'email_verified_at' => $date,
                'fname' => $full_name[0],
                'lname' => $full_name[1],
                'department' => $departments[array_rand($departments)],
                'reg_num' => $codes[array_rand($codes)] . $rand,
                'password' => Hash::make($password),
            ]);
        }
    }
}
