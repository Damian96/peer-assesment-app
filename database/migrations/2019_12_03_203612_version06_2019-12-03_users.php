<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Version0620191203Users extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement();
            $table->string('email', 255)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('fname', 255)->nullable();
            $table->string('lname', 255)->nullable();
            $table->string('department')->nullable();
            $table->string('reg_num', 7)->nullable();
            $table->char('instructor', 1)->default('0')
                ->comment('0 -> student, 1 -> instructor');
            $table->char('admin', 1)->default('0')->comment('boolean');
            $table->string('password', 255);
            $table->string('remember_token', 100)->nullable();
            $table->string('api_token', 80)->unique()->nullable()
                ->default(null);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('last_login')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
