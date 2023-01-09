<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReceiveFlagToWorkSpacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     *
     */
    public function up()
    {
        Schema::table('work_spaces', function (Blueprint $table) {
            $table->boolean('is_reserve')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('work_spaces', function (Blueprint $table) {
            $table->boolean('is_reserve')->default(0);
        });
    }
}
