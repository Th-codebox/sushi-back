<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkSpacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_spaces', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->longText('additional_info')->nullable();
            $table->unsignedInteger('filial_id');
            $table->unsignedInteger('role_id');


            $table->foreign('filial_id', 'ws_filial_id')
                ->on('filials')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('role_id', 'ws_role_id')
                ->on('roles')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('work_spaces', function (Blueprint $table) {
            $table->dropForeign('ws_filial_id');
            $table->drop();
        });

    }
}
