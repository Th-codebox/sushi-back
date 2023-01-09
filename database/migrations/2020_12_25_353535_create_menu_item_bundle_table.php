<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuItemBundleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_bundle_items', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedInteger('menu_item_bundle_id')->index();
            $table->unsignedInteger('menu_item_id')->index();
            $table->unsignedInteger('modification_menu_item_id')->nullable();

            $table->foreign('menu_item_id', 'mbi_menu_item_id_fk')
                ->on('menu_items')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');


            $table->foreign('modification_menu_item_id', 'mbi_mmi_id_fk')
                ->on('modification_menu_item')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_bundle_items', function (Blueprint $table) {

            $table->dropForeign('mbi_menu_item_id_fk');
            $table->dropForeign('mbi_mmi_id_fk');

            $table->drop();

        });
    }
}
