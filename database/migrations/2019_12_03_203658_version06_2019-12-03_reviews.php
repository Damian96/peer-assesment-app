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
            $table->bigInteger('recipient_id')->unsigned()->comment('none:1111');
            $table->bigInteger('question_id')->unsigned();
            $table->tinyInteger('mark')->nullable()->default('0')->comment('eval/linear-scale[0-100]');
            $table->char('type', 1)->comment('[e]valuation/[p]aragraph/[s]cale/[c]hoice/crite[r]ia');
            $table->text('comment')->nullable()->comment('paragraph');
            $table->string('answer', 255)->nullable()->default(null)->comment('multiple-choice');
            $table->timestamps();

            $table->foreign('sender_id')->references('id')->on('users');
            $table->foreign('recipient_id')->references('id')->on('users');
            $table->foreign('question_id')->references('id')->on('questions');
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
