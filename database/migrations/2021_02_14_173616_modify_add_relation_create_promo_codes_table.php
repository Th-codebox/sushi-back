<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyAddRelationCreatePromoCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_promo_codes', function (Blueprint $table) {


            $table->foreign('order_id', 'cpc_order_id_fk')
                ->on('orders')
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
        Schema::table('client_promo_codes', function (Blueprint $table) {


            $table->dropForeign('cpc_order_id_fk');


        });
    }
}
