<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGeoToFiliaslTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('filials', function (Blueprint $table) {
           $table->string('lat')->nullable();
           $table->string('let')->nullable();
           $table->string('work_phone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('filials', function (Blueprint $table) {
            //
        });
    }
}
