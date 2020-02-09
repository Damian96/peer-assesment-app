<?php

use App\StudentCourse;
use Illuminate\Database\Seeder;

class StudentCourseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Throwable
     */
    public function run()
    {
        /* `peerassessDB`.`student_course` */
        $student_course = array(
            array('user_id' => '4', 'course_id' => '2'),
            array('user_id' => '5', 'course_id' => '2'),
            array('user_id' => '6', 'course_id' => '2')
        );

        foreach ($student_course as $sc) {
            $model = new StudentCourse($sc);
            $model->saveOrFail();
        }
    }
}
