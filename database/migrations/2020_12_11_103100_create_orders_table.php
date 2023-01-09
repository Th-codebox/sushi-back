<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('client_id')->index();

            $table->unsignedInteger('manager_id')->nullable()->index();
            $table->unsignedInteger('courier_id')->nullable()->index();

            $table->unsignedInteger('client_address_id')->nullable();

            $table->unsignedInteger('basket_id');

            $table->unsignedInteger('filial_id');

            $table->integer('code')->default(1);

            $table->integer('kitchen_cell')->nullable();
            $table->integer('courier_cell')->nullable();

            $table->string('order_status')->nullable();

            $table->string('promo_code')->nullable();

            $table->text('delivery_type')->nullable();
            $table->text('payment_type')->nullable();

            $table->longText('additional_info')->nullable();

            $table->integer('total_price')->nullable();

            $table->integer('delivery_price')->nullable();
            $table->integer('cooking_and_delivery_time')->nullable();

            $table->text('client_money')->nullable();

            $table->timestamp('date')->nullable();
            $table->timestamp('dead_line')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('start_at')->nullable();

            $table->boolean('to_datetime')->default(0);
            $table->boolean('canceled_confirm_by_courier')->default(0);
            $table->timestamps();

            $table->softDeletes();

            $table->foreign('client_id', 'order_client_id')
                ->on('clients')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');


            $table->foreign('manager_id', 'order_manager_id')
                ->on('users')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('courier_id', 'order_courier_id')
                ->on('users')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('client_address_id', 'order_client_address_id')
                ->on('client_address')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');

            $table->foreign('basket_id', 'order_client_basket_id')
                ->on('baskets')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('filial_id', 'order_filial_id')
                ->on('filials')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('order_client_id');
            $table->dropForeign('order_manager_id');
            $table->dropForeign('order_courier_id');
            $table->dropForeign('order_client_address_id');
            $table->dropForeign('order_client_basket_id');
            $table->dropForeign('order_filial_id');
            $table->drop();
        });
    }
}
