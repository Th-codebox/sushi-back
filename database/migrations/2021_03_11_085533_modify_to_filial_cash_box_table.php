<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyToFilialCashBoxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('filial_cash_boxes', function (Blueprint $table) {
            $table->dropForeign('fcb_u_id');
            $table->dropColumn('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('filial_cash_boxes', function (Blueprint $table) {

            $table->unsignedInteger('user_id')->nullable();

        });
    }
}
