<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromoCodeToCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_code_to_category', function (Blueprint $table) {

            $table->unsignedInteger('category_id')->index();
            $table->unsignedInteger('promo_code_id')->index();

            $table->primary(['promo_code_id', 'category_id'], 'pc_category_primary');

            $table->foreign('promo_code_id', 'pcc_pc_id_fk')
                ->on('promo_codes')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('category_id', 'pcc_category_id_fk')
                ->on('categories')
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
        Schema::table('promo_code_to_category', function (Blueprint $table) {

            $table->dropPrimary('pc_category_primary');

            $table->dropForeign('pcc_pc_id_fk');
            $table->dropForeign('pcc_category_id_fk');

            $table->drop();

        });
    }
}
