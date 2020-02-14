<?php

use App\Course;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoursesTableSeeder extends Seeder
{
    const MAX = 10;
    private $table = 'courses';

    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        $instructors = array_column(User::getInstructors()->toArray(), 'id');
        if (empty($instructors)) {
            throw new Exception("No Instructors in `users` table." . "\n" . "aborting...");
        }

        DatabaseSeeder::refreshTable($this->table, true);

        $months = [1, 2, 3, 4, 10, 11, 12];
        $departments = ['CCP', 'CBE', 'CES', 'CPY'];
        $lipsum = " Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla sodales, est eu suscipit dictum, tellus mi imperdiet ex, sed maximus diam ante aliquam sem. Aenean id tincidunt lorem. Integer tincidunt eros lectus. Morbi blandit, est id interdum finibus, eros urna consequat elit, eu pulvinar mi tortor in nunc. Quisque imperdiet ipsum justo, ac molestie ante facilisis mattis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus et auctor neque, in molestie sem. Proin libero elit, ultrices nec urna quis, pharetra blandit massa. Nunc ac pharetra ipsum. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Duis interdum velit nulla, eget lobortis tellus hendrerit ac. Nam sollicitudin placerat dolor eget dignissim. Etiam pellentesque elementum ex, convallis bibendum mauris vehicula eget.Suspendisse sodales leo nisi, eu volutpat ligula molestie ut. Nunc sed accumsan odio. Nunc ornare facilisis sem sed hendrerit. Vivamus bibendum finibus magna, quis tincidunt nisi bibendum a. In faucibus commodo placerat. Vivamus tincidunt mauris at congue vestibulum. Sed quis est sed tortor posuere pulvinar. Sed placerat, orci at feugiat pulvinar, enim erat iaculis ex, eget dapibus mi nibh ac libero. Donec vulputate ultricies elementum. Aliquam erat volutpat. Ut leo lorem, tempus malesuada mauris et, dictum luctus dui. Nullam lacinia nisl nec convallis luctus. Donec dolor tortor, tristique ac elit tempus, mollis egestas lectus. Etiam tempus faucibus nibh, laoreet laoreet lectus rhoncus eget. Maecenas egestas felis id nulla tristique, sit amet eleifend tellus accumsan.";
        $years = range(config('constants.date.start'), intval(date('Y')));
        $range = range(1000, 1000 + self::MAX);
        for ($i = 1; $i <= self::MAX; $i++) {
            $code = $departments[array_rand($departments)] . array_pop($range);
            $time = Carbon::createFromDate($years[array_rand($years, 1)], $months[array_rand($months, 1)], rand(1, 15), config('app.timezone'))->timestamp;
            DB::table($this->table)->insert([
                'user_id' => $instructors[array_rand($instructors, 1)],
                'title' => substr($lipsum, rand(1, 15), (50 - $i)),
                'code' => $code,
                'status' => '1',
                'ac_year' => Course::toAcademicYear($time),
                'department' => $departments[array_rand($departments, 1)],
                'description' => substr($lipsum, 0, (100 - $i)),
            ]);
        }

        DB::table($this->table)->insert([
            'id' => Course::DUMMY_ID,
            'user_id' => 1,
            'code' => 'N/A',
            'status' => '0',
            'department' => 'N/A',
        ]);
    }
}
