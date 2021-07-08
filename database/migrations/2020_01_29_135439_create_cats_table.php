<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 100);
            $table->string('utm_name', 100)->nullable();
            $table->string('utm_value', 100)->nullable();
            $table->text('head_script')->nullable();
            $table->text('body_script')->nullable();
            $table->string('tizer_boost_geo', 100)->nullable();
            $table->string('news_boost_geo', 100)->nullable();
            $table->integer('tizer_boost_val')->nullable();
            $table->integer('news_boost_val')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
        Schema::dropIfExists('templates');
    }
}
