<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTizersTable extends Migration
{
    public function up()
    {
        Schema::create('tizers', function (Blueprint $table) {
            $table->increments('id');

            $table->string('title')->nullable();
            $table->string('color')->nullable();            

            $table->string('ext_link');

            $table->text('desc')->nullable();

            $table->integer('views')->default(0);

            $table->integer('clicks')->default(0);

            $table->float('aprove', 15, 2);

            $table->float('price', 15, 2);


            $table->timestamps();

            $table->softDeletes();
        });
    }
}
