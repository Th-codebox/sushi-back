<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserToFilialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_to_filial', function (Blueprint $table) {

            $table->unsignedInteger('user_id');
            $table->unsignedInteger('filial_id');

            $table->primary(['user_id', 'filial_id']);
            $table->index(['user_id', 'filial_id']);

            $table->foreign('user_id', 'utf_user_id_fk')
                ->on('users')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('filial_id', 'utf_filial_id_fk')
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
        Schema::table('user_to_filial', function (Blueprint $table) {
            $table->dropForeign('utf_user_id_fk');
            $table->dropForeign('utf_filial_id_fk');
            $table->drop();
        });
    }
}
