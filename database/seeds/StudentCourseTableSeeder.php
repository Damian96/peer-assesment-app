<?php

use App\Course;
use App\StudentCourse;
use Illuminate\Database\Seeder;

class StudentCourseTableSeeder extends Seeder
{
    private $table = 'student_course';

    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Throwable
     */
    public function run()
    {
        DatabaseSeeder::refreshTable($this->table, true);

        $course = Course::whereAcYear('SP-2020')->where('id', '!=', \App\Course::DUMMY_ID)
            ->firstOrFail();

        /* `peerassessDB`.`student_course` */
        $student_course = array(
            array('user_id' => '3', 'course_id' => $course->id),
            array('user_id' => '4', 'course_id' => $course->id),
            array('user_id' => '5', 'course_id' => $course->id),
        );

        foreach ($student_course as $sc) {
            $model = new StudentCourse($sc);
            $model->saveOrFail();
        }
    }
}
