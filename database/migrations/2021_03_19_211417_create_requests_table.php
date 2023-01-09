<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('filial_id')->nullable();
            $table->unsignedInteger('client_id')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('name')->nullable();
            $table->text('text')->nullable();
            $table->boolean('is_send')->nullable();
            $table->longText('additional_info')->nullable();
            $table->string('type')->default(\App\Enums\RequestType::Contact);
            $table->timestamps();


            $table->foreign('filial_id', 'r_f_id_fk')
                ->on('filials')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');


            $table->foreign('client_id', 'r_c_id_fk')
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
        Schema::table('filials', function (Blueprint $table) {
            $table->dropForeign('r_f_id_fk');
            $table->dropForeign('r_c_id_fk');
            $table->drop();

        });
    }
}
