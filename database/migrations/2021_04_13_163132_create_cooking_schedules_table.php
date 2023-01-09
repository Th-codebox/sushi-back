<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCookingSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cooking_schedules', function (Blueprint $table) {

            $table->increments('id');

            $table->boolean('cold_is_completed')->default(0);
            $table->boolean('hot_is_completed')->default(0);
            $table->boolean('assembly_is_completed')->default(0);

            $table->unsignedInteger('order_id');

            $table->foreign('order_id', 'cs_order_id')
                ->on('orders')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->softDeletes();

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
        Schema::dropIfExists('cooking_schedules');
    }
}
