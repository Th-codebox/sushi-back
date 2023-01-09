<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromoCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('code', 15);
            $table->string('description')->nullable();
            $table->string('action')->nullable();
            $table->string('type')->nullable();
            $table->integer('sale_percent')->default(0)->nullable();
            $table->integer('sale_subtraction')->default(0)->nullable();
            $table->unsignedInteger('sale_menu_item_id')->nullable();
            $table->unsignedInteger('sale_modification_menu_item_id')->nullable();
            $table->unsignedInteger('referrer_client_id')->nullable();
            $table->timestamp('time_on')->nullable();
            $table->timestamp('time_end')->nullable();
            $table->boolean('show_menu')->nullable();
            $table->boolean('only_first_order')->nullable();
            $table->boolean('status')->default(0);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();


            $table->foreign('sale_menu_item_id', 'pc_smi_id_fk')
                ->on('menu_items')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');

            $table->foreign('sale_modification_menu_item_id', 'pc_modification_id_fk')
                ->on('modification_menu_item')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');

            $table->foreign('referrer_client_id', 'pc_client_id_fk')
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
        Schema::table('promo_codes', function (Blueprint $table) {

            $table->dropForeign('pc_smi_id_fk');
            $table->dropForeign('pc_modification_id_fk');
            $table->dropForeign('pc_client_id_fk');

            $table->drop();

        });
    }
}
