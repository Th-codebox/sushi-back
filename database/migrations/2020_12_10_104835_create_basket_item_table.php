<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBasketItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('basket_items', function (Blueprint $table) {

            $table->increments('id');

            $table->unsignedInteger('basket_id');

            $table->unsignedInteger('modification_menu_item_id')->nullable();

            $table->unsignedInteger('menu_item_id')->nullable();

            $table->unsignedInteger('sub_menu_item_id')->nullable();


            $table->text('comment')->nullable();

          //  $table->integer('quantity')->nullable();

            $table->integer('price')->nullable();

            $table->string('type')->default('usual');
            $table->boolean('free')->default(0);

            $table->foreign('basket_id', 'bi_b_id')
                ->on('baskets')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');


            $table->foreign('menu_item_id', 'bi_mi_id')
                ->on('menu_items')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');

            $table->foreign('sub_menu_item_id', 'bi_smi_id')
                ->on('menu_items')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');


            $table->foreign('modification_menu_item_id', 'bi_mmi_id_fk')
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
        Schema::table('basket_items', function (Blueprint $table) {

            $table->dropForeign('bi_b_id');
            $table->dropForeign('bi_mi_id');
            $table->dropForeign('bi_smi_id');
            $table->dropForeign('bi_mmi_id_fk');

            $table->drop();

        });
    }
}
