<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modifications', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('technical_card_id')->nullable();
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->string('action', 255);
            $table->float('price_rate')->default(1);
            $table->integer('price_add')->default(0);
            $table->string('type');
            $table->string('name_off',255)->nullable();
            $table->string('name_on',255)->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('status')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('technical_card_id','m_tc_id')
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
        Schema::table('modifications', function (Blueprint $table) {

            $table->dropForeign('m_tc_id');

            $table->drop();

        });
    }
}
