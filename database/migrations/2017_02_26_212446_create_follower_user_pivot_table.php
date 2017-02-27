<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFollowerUserPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('follower_user', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('follower_id');

        });
        Schema::table('follower_user', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('follower_id')->references('id')->on('followers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('follower_user', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['follower_id']);
        });
        Schema::drop('follower_user');
    }
}
