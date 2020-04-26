<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentSessionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_session', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('session_id');
            $table->tinyInteger('mark', false, true)->default(0);
            $table->timestamp('created_at')->nullable()->default(DB::raw('NULL'));
            $table->timestamp('updated_at')->nullable()->default(DB::raw('NULL'));

            if (env('APP_ENV', 'local') !== 'testing') {
                $table->foreign('user_id', 'student_session_users_foreign')->references('id')->on('users');
                $table->foreign('session_id', 'student_session_sessions_foreign')->references('id')->on('sessions');
            }
        });

        DB::statement('ALTER TABLE `student_session` ADD UNIQUE `student_session_unique` (`user_id`, `session_id`) using BTREE;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_session');
    }
}
