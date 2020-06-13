<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Version0620191203Sessions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned()->autoIncrement();
            $table->bigInteger('course_id')->unsigned();
            $table->string('title', 255);
            $table->timestamp('deadline');
            $table->text('instructions');
            $table->float('mark_avg')->nullable()->default(0)->comment('average mark');
            $table->tinyInteger('groups')->comment('maximum groups');
            $table->tinyInteger('min_group_size')->comment('maximum group size');
            $table->tinyInteger('max_group_size')->comment('minimum group size');
            $table->timestamp('open_date')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamps();

            $table->foreign('course_id', 'sessions_courses_foreign')->references('id')->on('courses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sessions');
    }
}
