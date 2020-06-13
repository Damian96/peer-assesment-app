<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Version0620191203Forms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned()->autoIncrement();
            $table->bigInteger('session_id')->unsigned()->nullable()->default(0);
            $table->string('title', 255);
            $table->string('subtitle', 255)->nullable();
            $table->char('mark', 1)->nullable()->default('0')->comment('class mark');
            $table->timestamps();

            $table->foreign('session_id', 'forms_sessions_foreign')->references('id')->on('sessions')
                ->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('forms');
    }
}
