<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filials', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('city')->default('Санкт-петербург');
            $table->string('requisites')->nullable();
            $table->string('address')->nullable();
            $table->integer('min_order_cost')->default(0);
            $table->integer('sort_order')->default(0);
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('filials');
    }
}
