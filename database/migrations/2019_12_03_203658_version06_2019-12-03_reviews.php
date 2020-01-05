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
            $table->bigInteger('recipient_id')->unsigned();
            $table->bigInteger('question_id')->unsigned();
            $table->char('mark', 1)->nullable()->default('0')->comment('0-5');
            $table->text('comment')->nullable();
            $table->char('answer', 1)->nullable()->default('0')->comment('boolean');
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
