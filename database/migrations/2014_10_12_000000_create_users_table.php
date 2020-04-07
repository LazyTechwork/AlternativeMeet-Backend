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
            $table->string('firstname')->nullable()->comment('Имя');
            $table->string('lastname')->nullable()->comment('Фамилия');
            $table->text('description')->nullable()->comment('Описание профиля');
            $table->date('birthday')->nullable()->comment('Дата рождения');
            $table->unsignedTinyInteger('sex')->default(0)->comment('Пол');

//            Additional info
            $table->json('social')->nullable()->comment('Объект соц сетей');
            $table->json('geo')->nullable()->comment('Геопозиция (lat, long)');
            $table->json('photo')->nullable()->comment('Фото профиля');

//            Interests
            $table->smallInteger('agefrom')->nullable()->comment('Интересы: нижний возраст');
            $table->smallInteger('ageto')->nullable()->comment('Интересы: верхний возраст');
            $table->foreignId('tag_id')->nullable()->comment('Интересы: тег');

//            Technical info
            $table->unsignedBigInteger('vk_id')->comment('ID пользователя ВКонтакте');
            $table->string('vk_token')->nullable()->comment('Хеш-токен');
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
