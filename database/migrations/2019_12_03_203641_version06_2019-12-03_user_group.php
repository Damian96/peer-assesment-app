<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Version0620191203UserGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_group', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true);
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('group_id')->unsigned();
            $table->timestamp('created_at')->nullable()->default(DB::raw('NULL'));
            $table->timestamp('updated_at')->nullable()->default(DB::raw('NULL'));

            $table->foreign('user_id', 'user_group_users_foreign')->references('id')->on('users');
            $table->foreign('group_id', 'user_group_groups_foreign')->references('id')->on('groups')
                ->onDelete('CASCADE');
        });

        DB::statement('ALTER TABLE `user_group` ADD UNIQUE `user_group_unique` (`user_id`, `group_id`) using BTREE;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_group');
    }
}
