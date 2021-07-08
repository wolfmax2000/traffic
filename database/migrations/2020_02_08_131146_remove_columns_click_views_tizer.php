<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveColumnsClickViewsTizer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tizers', function (Blueprint $table) {
            $table->dropColumn('views');
            $table->dropColumn('clicks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tizers', function (Blueprint $table) {
            $table->integer('views')->default(0);
            $table->integer('clicks')->default(0);
        });
    }
}
