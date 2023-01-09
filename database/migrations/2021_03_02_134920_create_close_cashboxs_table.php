<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCloseCashboxsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filial_cash_boxes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('filial_id');
            $table->unsignedInteger('user_id');
            $table->date('date');
            $table->dateTime('open_at')->useCurrent();
            $table->dateTime('close_at')->nullable();
            $table->integer('begin_cash');
            $table->integer('begin_terminal');
            $table->integer('begin_checks');
            $table->integer('end_cash');
            $table->integer('end_terminal');
            $table->integer('end_checks');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id', 'fcb_u_id')
                ->on('users')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('filial_id', 'fcb_f_id')
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
        Schema::table('cash_boxes', function (Blueprint $table) {
            $table->dropForeign('fcb_f_id');
            $table->drop();
        });
    }
}
