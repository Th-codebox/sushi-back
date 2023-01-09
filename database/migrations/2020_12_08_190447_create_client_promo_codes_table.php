<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientPromoCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_promo_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('promo_code_id');
            $table->unsignedInteger('order_id')->nullable();
            $table->boolean('activated')->default(0);
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('dead_line')->nullable();
            $table->timestamps();
            $table->softDeletes();


            $table->foreign('client_id', 'cpc_client_id_fk')
                ->on('clients')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('promo_code_id', 'cpc_pc_id_fk')
                ->on('promo_codes')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('order_id', 't_co_id')
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

            $table->dropForeign('cpc_client_id_fk');
            $table->dropForeign('cpc_pc_id_fk');


            $table->drop();

        });
    }
}
