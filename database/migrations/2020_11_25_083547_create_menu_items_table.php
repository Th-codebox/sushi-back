<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('technical_card_id')->nullable();
            $table->string('name', 255);
            $table->string('slug');
            $table->string('type')->default('single');
            $table->longText('description')->nullable();
            $table->text('h1')->nullable();
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('composition')->nullable();
            $table->integer('price')->nullable();
            $table->integer('old_price')->nullable();
            $table->integer('bonus_add')->nullable();
            $table->boolean('has_stop')->default(0);
            $table->string('image', 255)->nullable();
            $table->string('sticker_type', 255)->nullable();
            $table->string('sticker_marketing', 255)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('status')->default(0);
            $table->boolean('need_person_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('technical_card_id', 'mi_tech_card_id')
                ->on('technical_cards')
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
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropForeign('mi_tech_card_id');
            $table->drop();
        });
    }
}
