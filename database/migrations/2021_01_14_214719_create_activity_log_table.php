<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityLogTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::connection(config('activitylog.database_connection'))->create(config('activitylog.table_name'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('utm_id')->nullable();
            $table->string('log_name')->nullable();
            $table->text('description');
            $table->nullableMorphs('subject', 'subject');
            $table->nullableMorphs('causer', 'causer');
            $table->longText('properties')->nullable();
            $table->timestamps();
            $table->index('log_name');


            $table->foreign('utm_id', 'al_utm_id')
                ->on('utms')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table(config('activitylog.table_name'), function (Blueprint $table) {
            $table->dropForeign('al_utm_id');
            $table->drop();
        });

    }
}
