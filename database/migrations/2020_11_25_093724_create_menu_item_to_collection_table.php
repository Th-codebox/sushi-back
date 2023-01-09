<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuItemToCollectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_item_to_collection', function (Blueprint $table) {

            $table->unsignedInteger('menu_item_id')->index();
            $table->unsignedInteger('collection_id')->index();

            $table->primary(['menu_item_id', 'collection_id'], 'mi_collection_primary');

            $table->foreign('menu_item_id', 'collection_mi_mi_id_fk')
                ->on('menu_items')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('collection_id', 'collection_mi_collection_id_fk')
                ->on('collections')
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
        Schema::table('menu_item_to_collection', function (Blueprint $table) {

            $table->dropPrimary('mi_collection_primary');

            $table->dropForeign('collection_mi_mi_id_fk');
            $table->dropForeign('collection_mi_collection_id_fk');

            $table->drop();

        });
    }
}
