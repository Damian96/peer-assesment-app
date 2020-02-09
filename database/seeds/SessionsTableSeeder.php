<?php

use App\Course;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SessionsTableSeeder extends Seeder
{
    const MAX = 15;
    private $table = 'sessions';

    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        DatabaseSeeder::refreshTable($this->table, true);

        $courses = Course::getCurrentYears()->get(['courses.*']);
        $lipsum = " Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla sodales, est eu suscipit dictum, tellus mi imperdiet ex, sed maximus diam ante aliquam sem. Aenean id tincidunt lorem. Integer tincidunt eros lectus. Morbi blandit, est id interdum finibus, eros urna consequat elit, eu pulvinar mi tortor in nunc. Quisque imperdiet ipsum justo, ac molestie ante facilisis mattis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus et auctor neque, in molestie sem. Proin libero elit, ultrices nec urna quis, pharetra blandit massa. Nunc ac pharetra ipsum. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Duis interdum velit nulla, eget lobortis tellus hendrerit ac. Nam sollicitudin placerat dolor eget dignissim. Etiam pellentesque elementum ex, convallis bibendum mauris vehicula eget.Suspendisse sodales leo nisi, eu volutpat ligula molestie ut. Nunc sed accumsan odio. Nunc ornare facilisis sem sed hendrerit. Vivamus bibendum finibus magna, quis tincidunt nisi bibendum a. In faucibus commodo placerat. Vivamus tincidunt mauris at congue vestibulum. Sed quis est sed tortor posuere pulvinar. Sed placerat, orci at feugiat pulvinar, enim erat iaculis ex, eget dapibus mi nibh ac libero. Donec vulputate ultricies elementum. Aliquam erat volutpat. Ut leo lorem, tempus malesuada mauris et, dictum luctus dui. Nullam lacinia nisl nec convallis luctus. Donec dolor tortor, tristique ac elit tempus, mollis egestas lectus. Etiam tempus faucibus nibh, laoreet laoreet lectus rhoncus eget. Maecenas egestas felis id nulla tristique, sit amet eleifend tellus accumsan.";
        for ($i = 1; $i <= self::MAX; $i++) {
            DB::table($this->table)->insert([
                'course_id' => $courses[$i]->id,
                'title' => "{$courses[$i]->code}-Session-{$courses[$i]->id}",
                'instructions' => substr($lipsum, -(rand(0, 50))),
                'deadline' => Carbon::now(config('app.timezone'))->addSeconds(5)->addMonths(rand(1, 5)),
            ]);
        }

        DB::table($this->table)->insert([
            'id' => 1111,
            'course_id' => 1111,
            'title' => 'N/A',
            'instructions' => 'N/A',
        ]);
    }
}
