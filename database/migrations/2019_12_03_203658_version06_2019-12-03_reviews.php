<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Version0620191203Reviews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned()->autoIncrement();
            $table->bigInteger('sender_id')->unsigned();
            $table->bigInteger('recipient_id')->unsigned()->nullable()->default(0);
            $table->bigInteger('question_id')->unsigned();
            $table->tinyInteger('mark')->nullable()->default('0')->comment('0-100');
            $table->text('comment')->nullable();
            $table->string('answer', 255)->nullable()->default(null)->comment('multiple-choice');
            $table->timestamps();

            $table->index('sender_id');
            $table->index('question_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
