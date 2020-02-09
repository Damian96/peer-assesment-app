<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Version0620191203Courses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->unsignedBigInteger('user_id');
            $table->string('title', 50)->nullable();
            $table->char('status', 1)->default('1');
            $table->string('code', 10)->nullable();
            $table->string('description', 150)->nullable();
            $table->string('department', 50);
            $table->string('ac_year', 7)->default(DB::raw('YEAR(CURRENT_TIMESTAMP)'))->comment('[SE]-[YYYY]');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->foreign('user_id')->references('id')->on('users');
            $table->index('user_id');
            $table->index('ac_year');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
}
