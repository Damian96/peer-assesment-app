<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Version0620191203StudentCourse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_course', function (Blueprint $table) {
            $table->bigInteger('id', true, true);
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('course_id')->unsigned();
            $table->timestamps();

            $table->index('user_id', 'user_id_index');
            $table->index('course_id', 'course_id_index');
            $table->foreign('user_id', 'student_course_users_foreign')->references('id')->on('users');
            $table->foreign('course_id', 'student_course_courses_foreign')->references('id')->on('courses');
        });

//        DB::statement('ALTER TABLE `student_course` ADD UNIQUE `student_course_unique` (`user_id`, `course_id`) using BTREE;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_course');
    }
}
