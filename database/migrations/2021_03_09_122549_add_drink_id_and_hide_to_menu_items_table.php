<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDrinkIdAndHideToMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_items', function (Blueprint $table) {

            $table->unsignedInteger('souse_id')->nullable();

            $table->foreign('souse_id', 'mi_s_id')
                ->on('menu_items')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');

            $table->boolean('hide')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropForeign('mi_s_id');
        });
    }
}
