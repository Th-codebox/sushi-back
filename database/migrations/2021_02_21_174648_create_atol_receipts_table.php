<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAtolReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('atol_receipts', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('order_id')->nullable();
            $table->uuid('uuid')->nullable();
            $table->text('request_object');
            $table->string('status')->nullable();
            $table->text('report_object')->nullable();

            $table->timestamps();

            $table->foreign('order_id', 'atol_receipt_order_id')
                ->on('orders')
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
        Schema::dropIfExists('atol_receipts');
    }
}
