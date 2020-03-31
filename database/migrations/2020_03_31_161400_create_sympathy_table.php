<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSympathyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sympathy', function (Blueprint $table) {
            $table->foreignId('from')->comment('Симпатия от');
            $table->foreignId('to')->comment('Симпатия к');
            $table->tinyInteger('status')->default(0)->comment('Статус симпатии');

//            Foreign keys
            $table->foreign('from')->references('id')->on('users');
            $table->foreign('to')->references('id')->on('users');

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
        Schema::dropIfExists('sympathy');
    }
}
