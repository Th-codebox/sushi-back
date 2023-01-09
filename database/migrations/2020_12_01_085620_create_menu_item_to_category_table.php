<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuItemToCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('menu_item_to_category', function (Blueprint $table) {

            $table->unsignedInteger('menu_item_id')->index();
            $table->unsignedInteger('category_id')->index();

            $table->primary(['menu_item_id', 'category_id'], 'mi_category_primary');

            $table->foreign('menu_item_id', 'category_mi_mi_id_fk')
                ->on('menu_items')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('category_id', 'category_mi_category_id_fk')
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
        Schema::table('menu_item_to_category', function (Blueprint $table) {

            $table->dropPrimary('mi_category_primary');

            $table->dropForeign('category_mi_mi_id_fk');
            $table->dropForeign('category_mi_category_id_fk');

            $table->drop();

        });
    }
}
