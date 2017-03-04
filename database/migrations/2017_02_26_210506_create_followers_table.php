<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFollowersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('followers', function (Blueprint $table) {
            $table->unsignedInteger('id')->unique();
            $table->string('username')->nullable();
            $table->string('name')->nullable();
            $table->string('avatar')->nullable();
            $table->unsignedInteger('followers')->default(0);
            $table->unsignedInteger('affection')->default(0);

            $table->timestamps();

            $table->index(['followers', 'affection']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('followers');
    }
}
