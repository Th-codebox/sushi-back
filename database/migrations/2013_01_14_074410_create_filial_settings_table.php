<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilialSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filial_settings', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedInteger('filial_id');
            $table->unsignedInteger('setting_id');


            $table->longText('value')->nullable();
            $table->boolean('json')->default(0);

            $table->timestamps();


            $table->foreign('filial_id', 'fs_f_id_fk')
                ->on('filials')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');


            $table->foreign('setting_id', 'fs_s_id_fk')
                ->on('settings')
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
        Schema::table('filial_settings', function (Blueprint $table) {

            $table->dropForeign('fs_f_id_fk');
            $table->dropForeign('fs_s_id_fk');

            $table->drop();
        });
    }
}
