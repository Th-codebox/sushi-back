<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_address', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->nullable();
            $table->string('ico_name', 255)->nullable();
            $table->string('apartment_number', 255)->nullable();
            $table->string('city', 255)->nullable();
            $table->string('street', 255)->nullable();
            $table->string('house', 255)->nullable();
            $table->string('entry', 255)->nullable();
            $table->string('lat_geo', 255)->nullable();
            $table->string('let_geo', 255)->nullable();
            $table->timestamps();

            $table->softDeletes();

            $table->unsignedInteger('client_id')->index();

            $table->foreign('client_id', 'ca_client_id_fk')
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
        Schema::table('client_address', function (Blueprint $table) {
            $table->dropForeign('ca_client_id_fk');
            $table->drop();
        });
    }
}
