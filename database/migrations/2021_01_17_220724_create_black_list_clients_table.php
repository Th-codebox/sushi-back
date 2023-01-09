<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlackListClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('black_list_clients', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('client_id');
            $table->boolean('status_block')->default(1);
            $table->dateTime('end_blocking')->nullable();
            $table->text('reason')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('client_id', 'blc_client_id')
                ->on('clients')
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
        Schema::table('black_list_clients', function (Blueprint $table) {
            $table->dropForeign('blc_client_id');
            $table->drop();
        });
    }
}
