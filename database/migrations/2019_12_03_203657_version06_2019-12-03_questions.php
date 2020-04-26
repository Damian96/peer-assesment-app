<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Version0620191203Questions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned()->autoIncrement();
            $table->bigInteger('form_id')->unsigned();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('data');
            $table->timestamp('created_at')->nullable()->default(DB::raw('NULL'));
            $table->timestamp('updated_at')->nullable()->default(DB::raw('NULL'));

            if (env('APP_ENV', 'local') !== 'testing') {
                $table->foreign('form_id', 'questions_forms_foreign')->references('id')->on('forms');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
