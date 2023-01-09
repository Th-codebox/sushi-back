<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBasketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baskets', function (Blueprint $table) {

            $table->increments('id');

            $table->unsignedInteger('client_id')->index();

            $table->unsignedInteger('client_address_id')->nullable();

            $table->unsignedInteger('utm_id')->nullable();

            $table->unsignedInteger('client_promo_code_id')->nullable();

            $table->unsignedInteger('previous_client_address_id')->nullable();

            $table->unsignedInteger('previous_filial_id')->nullable();

            $table->unsignedInteger('filial_id')->nullable();

            $table->string('promo_code')->nullable();
            $table->text('delivery_type')->nullable();
            $table->string('payment_type')->nullable();
            $table->integer('total_price')->nullable();
            $table->integer('delivery_price')->nullable();
            $table->integer('free_delivery')->nullable();
            $table->integer('persons')->nullable();
            $table->text('comment')->nullable();
            $table->text('comment_for_courier')->nullable();
            $table->text('client_source')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('ip')->nullable();
            $table->string('status')->nullable();
            $table->text('client_money')->nullable();
            $table->dateTime('date_delivery')->nullable();
            $table->string('time_delivery')->nullable();
            $table->string('recipient_name')->nullable();
            $table->string('recipient_phone')->nullable();
            $table->integer('time_in_delivery')->nullable();
            $table->boolean('no_call')->default(0);
            $table->boolean('to_datetime')->default(0);

            $table->string('basket_source')->default('web');

            $table->timestamps();

            $table->softDeletes();

            $table->foreign('client_id', 'b_client_id')
                ->on('clients')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('client_address_id', 'b_client_address_id')
                ->on('client_address')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');

            $table->foreign('utm_id', 'b_utm_id')
                ->on('utms')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');

            $table->foreign('client_promo_code_id', 'b_cpc_id')
                ->on('client_promo_codes')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');

            $table->foreign('previous_client_address_id', 'b_pca_id')
                ->on('client_address')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');


            $table->foreign('previous_filial_id', 'b_pf_id')
                ->on('filials')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');


            $table->foreign('filial_id', 'b_f_id')
                ->on('filials')
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
        Schema::table('baskets', function (Blueprint $table) {
            $table->dropForeign('b_client_id');
            $table->dropForeign('b_client_address_id');
            $table->dropForeign('b_utm_id');
            $table->dropForeign('b_pca_id');
            $table->dropForeign('b_pf_id');
            $table->dropForeign('b_cpc_id');
            $table->dropForeign('b_f_id');
            $table->drop();
        });
    }
}
