<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCallIdToBasketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('baskets', function (Blueprint $table) {

            $table->unsignedInteger('call_id')->nullable();

            $table->foreign('call_id', 'b_call_id_fk')
                ->on('calls')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('baskets', function (Blueprint $table) {
           $table->dropForeign('b_call_id_fk');
        });
    }
}
