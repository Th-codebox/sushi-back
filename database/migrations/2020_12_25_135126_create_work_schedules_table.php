<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_schedules', function (Blueprint $table) {

            $table->increments('id');

            $table->unsignedInteger('work_space_id')->nullable();

            $table->date('date');
            $table->time('begin');
            $table->time('end');
            $table->string('shift_time')->default(\App\Enums\ShiftTime::OffDay);

            $table->unsignedInteger('user_id');

            $table->foreign('work_space_id', 'ws_ws_id')
                ->on('work_spaces')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');


            $table->foreign('user_id', 'ws_c_id')
                ->on('users')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');


            $table->boolean('status')->default(0);
            $table->integer('sort_order')->default(0);

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
        Schema::table('work_schedules', function (Blueprint $table) {
            $table->dropForeign('ws_c_id');
            $table->dropForeign('ws_ws_id');
            $table->drop();
        });
    }
}
