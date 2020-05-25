<?php

use App\Course;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SessionsTableSeeder extends Seeder
{
    const MAX = 5;
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

        $lipsum = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla sodales, est eu suscipit dictum, tellus mi imperdiet ex, sed maximus diam ante aliquam sem. Aenean id tincidunt lorem. Integer tincidunt eros lectus. Morbi blandit, est id interdum finibus, eros urna consequat elit, eu pulvinar mi tortor in nunc. Quisque imperdiet ipsum justo, ac molestie ante facilisis mattis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus et auctor neque, in molestie sem. Proin libero elit, ultrices nec urna quis, pharetra blandit massa. Nunc ac pharetra ipsum. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Duis interdum velit nulla, eget lobortis tellus hendrerit ac. Nam sollicitudin placerat dolor eget dignissim. Etiam pellentesque elementum ex, convallis bibendum mauris vehicula eget.Suspendisse sodales leo nisi, eu volutpat ligula molestie ut. Nunc sed accumsan odio. Nunc ornare facilisis sem sed hendrerit. Vivamus bibendum finibus magna, quis tincidunt nisi bibendum a. In faucibus commodo placerat. Vivamus tincidunt mauris at congue vestibulum. Sed quis est sed tortor posuere pulvinar. Sed placerat, orci at feugiat pulvinar, enim erat iaculis ex, eget dapibus mi nibh ac libero. Donec vulputate ultricies elementum. Aliquam erat volutpat. Ut leo lorem, tempus malesuada mauris et, dictum luctus dui. Nullam lacinia nisl nec convallis luctus. Donec dolor tortor, tristique ac elit tempus, mollis egestas lectus. Etiam tempus faucibus nibh, laoreet laoreet lectus rhoncus eget. Maecenas egestas felis id nulla tristique, sit amet eleifend tellus accumsan.";
        if (!strcmp(env('APP_ENV', 'local'), 'local')) {
            $courses = Course::getCurrentYears()->get(['courses.*']);
            foreach ($courses as $course) {
                DB::table($this->table)->insert([
                    'course_id' => $course->id,
                    'title' => "{$course->code}-Session-{$course->id}",
                    'instructions' => substr($lipsum, -(rand(0, 50))),
                    'deadline' => Carbon::now(config('app.timezone'))->addMonths(rand(2, 4))->format(config('constants.date.stamp')),
                    'open_date' => Carbon::now(config('app.timezone'))->addMonths(rand(0, 1))->format(config('constants.date.stamp')),
                    'groups' => 2,
                    'min_group_size' => 2,
                    'max_group_size' => 3,
                ]);
            }
        } else if (!strcmp(env('APP_ENV', 'local'), 'testing')) {
            /* `wpesdb`.`sessions` */
            $sessions = array(
                array('course_id' => '1', 'title' => 'CW1-RTC_Chat_App_Java', 'deadline' => '2020-05-04 00:00:00', 'instructions' => 'Please complete all peer assessment fields. Any feedback about the course is always welcomed!', 'mark_avg' => '0.00', 'groups' => '2', 'min_group_size' => '3', 'max_group_size' => '3', 'open_date' => '2020-05-03 00:00:00', 'created_at' => '2020-04-25 14:40:26', 'updated_at' => '2020-04-25 14:40:26')
            );
            foreach ($sessions as $session) {
                DB::table($this->table)->insert([
                    'course_id' => $session['course_id'],
                    'title' => $session['title'],
                    'instructions' => substr($lipsum, -(rand(0, 50))),
                    'deadline' => Carbon::now(config('app.timezone'))->addMonths(rand(2, 4))->format(config('constants.date.stamp')),
                    'open_date' => Carbon::now(config('app.timezone'))->addDays(rand(1, 5))->format(config('constants.date.stamp')),
                    'groups' => 2,
                    'min_group_size' => 2,
                    'max_group_size' => 3,
                ]);
            }
        }

        // Insert DUMMY session
        DB::table($this->table)->insert([
            'id' => Course::DUMMY_ID,
            'course_id' => Course::DUMMY_ID,
            'title' => 'N/A',
            'instructions' => 'N/A',
            'groups' => 2,
            'min_group_size' => 2,
            'max_group_size' => 3,
        ]);
    }
}
