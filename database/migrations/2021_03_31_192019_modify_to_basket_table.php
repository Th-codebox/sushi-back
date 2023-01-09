<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyToBasketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('baskets', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->index();
            $table->dropForeign('b_client_id');
            $table->unsignedInteger('client_id')->nullable()->change();
            $table->foreign('client_id', 'b_client_id')
                ->on('clients')
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
        Schema::table('basket', function (Blueprint $table) {
            //
        });
    }
}
