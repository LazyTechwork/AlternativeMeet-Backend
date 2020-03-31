<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vk_id');
            $table->string('firstname');
            $table->string('lastname');
            $table->text('description')->nullable();
            $table->json('geo')->nullable();
            $table->date('birthday')->nullable();
            $table->json('social')->nullable();
            $table->string('avatar')->nullable();
            $table->unsignedTinyInteger('sex')->default(0);
            $table->string('vk_token');
            $table->timestamps();
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
