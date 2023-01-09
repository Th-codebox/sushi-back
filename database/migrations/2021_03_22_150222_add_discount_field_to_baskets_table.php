<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscountFieldToBasketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('baskets', function (Blueprint $table) {
            $table->integer('discount_amount')->nullable()->default(0);
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('discount_amount')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('baskets', function (Blueprint $table) {
            //
        });
    }
}
