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
            $table->bigInteger('recipient_id')->unsigned()->comment('none:11');
            $table->bigInteger('question_id')->unsigned();
            $table->bigInteger('session_id')->unsigned();
            $table->string('title', 255)->comment('question title');
            $table->tinyInteger('mark')->nullable()->default('0');
            $table->char('type', 1)->comment('[e]valuation/[p]aragraph/likert-[s]cale/[c]hoice/crite[r]ia');
            $table->text('comment')->nullable()->comment('paragraph');
            $table->string('answer', 255)->nullable()->default(null)->comment('multiple-choice');
            $table->timestamp('created_at')->nullable()->default(DB::raw('NULL'));
            $table->timestamp('updated_at')->nullable()->default(DB::raw('NULL'));

            if (env('APP_ENV', 'local') !== 'testing') {
                $table->foreign('sender_id', 'reviews_users_sender_foreign')->references('id')->on('users');
                $table->foreign('recipient_id', 'reviews_users_recipient_foreign')->references('id')->on('users');
                $table->foreign('question_id', 'reviews_questions_foreign')->references('id')->on('questions')
                    ->onDelete('CASCADE');
            }
            $table->index('sender_id');
            $table->index('question_id');
            $table->index('session_id');
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
