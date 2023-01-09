<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calls', function (Blueprint $table) {
            $table->increments('id');
            $table->string('call_id')->index();
            $table->integer('account_id');
            $table->string('incoming_phone');
            $table->boolean('is_active_client')->default(0);
            $table->string('client_name')->nullable();
            $table->unsignedInteger('client_id')->nullable();
            $table->boolean('has_order_today')->default(0);
            $table->timestamps();


            $table->foreign('client_id', 'c_client_id_fk')
                ->on('clients')
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
        Schema::table('calls', function (Blueprint $table) {


            $table->dropForeign('c_client_id_fk');


        });
    }
}
