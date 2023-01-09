<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModificationMenuItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modification_menu_item', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('modification_id')->index();
            $table->unsignedInteger('menu_item_id')->index();
            $table->float('price_rate')->default(1);
            $table->integer('price_add')->default(0);


            $table->foreign('menu_item_id', 'mmi_mi_id_fk')
                ->on('menu_items')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('modification_id', 'mmi_modification_id_fk')
                ->on('modifications')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->integer('sort_order')->default(0);
            $table->boolean('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('modification_menu_item', function (Blueprint $table) {

            $table->dropForeign('mmi_mi_id_fk');
            $table->dropForeign('mmi_modification_id_fk');

            $table->drop();

        });
    }
}
