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

//            Profile info
            $table->string('firstname');
            $table->string('lastname');
            $table->text('description')->nullable();
            $table->date('birthday')->nullable();
            $table->unsignedTinyInteger('sex')->default(0);

//            Additional info
            $table->json('social')->nullable();
            $table->json('geo')->nullable();
            $table->string('avatar')->nullable();

//            Interests
            $table->smallInteger('agefrom');
            $table->smallInteger('ageto');
            $table->foreignId('tag_id');

//            Technical info
            $table->unsignedBigInteger('vk_id');
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
