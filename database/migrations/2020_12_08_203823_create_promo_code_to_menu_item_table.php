<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromoCodeToMenuItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_code_to_menu_item', function (Blueprint $table) {

            $table->unsignedInteger('menu_item_id')->index();
            $table->unsignedInteger('promo_code_id')->index();

            $table->primary(['menu_item_id', 'promo_code_id'], 'mi_pc_primary');

            $table->foreign('menu_item_id', 'comi_mi_id_fk')
                ->on('menu_items')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('promo_code_id', 'comi_pc_id_fk')
                ->on('promo_codes')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('promo_code_to_menu_item', function (Blueprint $table) {

            $table->dropPrimary('mi_pc_primary');

            $table->dropForeign('comi_mi_id_fk');
            $table->dropForeign('comi_pc_id_fk');

            $table->drop();

        });
    }
}
