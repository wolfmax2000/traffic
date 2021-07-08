<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTable extends Migration
{
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->increments('id');

            $table->string('title');
            $table->string('color')->nullable();            

            $table->longText('short_desc')->nullable();

            $table->longText('desc');

            $table->integer('views')->default(0);

            $table->integer('clicks')->default(0);

            $table->timestamps();

            $table->softDeletes();
        });
    }
}
