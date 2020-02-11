<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_templates', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned()->autoIncrement();
            $table->bigInteger('user_id')->unsigned()->nullable()->default(1111);
            $table->string('title', 255);
            $table->string('subtitle', 255)->nullable();
            $table->string('footnote', 255)->nullable();
            $table->text('questions');
            $table->timestamps();

            $table->index('user_id', 'form_template_user_id_index');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_templates');
    }
}
