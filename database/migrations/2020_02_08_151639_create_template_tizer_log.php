<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemplateTizerLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('template_tizer_views', function (Blueprint $table) {            
            $table->increments('id');
            $table->unsignedInteger('template_id');
            $table->foreign('template_id', 'template_id_fk_847234')->references('id')->on('templates')->onDelete('cascade');
            $table->unsignedInteger('tizer_id');
            $table->foreign('tizer_id', 'tizer_id_fk_847234')->references('id')->on('tizers')->onDelete('cascade');
            $table->timestamp('time')->useCurrent = true;            
        });

        Schema::create('template_tizer_clicks', function (Blueprint $table) {            
            $table->increments('id');
            $table->unsignedInteger('template_id');
            $table->foreign('template_id', 'template_id_fk_847434')->references('id')->on('templates')->onDelete('cascade');
            $table->unsignedInteger('tizer_id');
            $table->foreign('tizer_id', 'tizer_id_fk_847434')->references('id')->on('tizers')->onDelete('cascade');
            $table->timestamp('time')->useCurrent = true;
            
        });

        Schema::table('templates', function (Blueprint $table) {
            $table->integer('stat_days')->default(14);
        });

        Schema::table('tizers', function (Blueprint $table) {
            $table->boolean('is_active')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('template_tizer_views');
        Schema::dropIfExists('template_tizer_clicks');     
        
        Schema::table('templates', function (Blueprint $table) {
            $table->dropColumn('stat_days');
        });

        Schema::table('tizers', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
}
