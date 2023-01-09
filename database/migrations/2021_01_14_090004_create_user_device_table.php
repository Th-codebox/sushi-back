<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDeviceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_devices', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->text('agent')->nullable();
            $table->text('device')->nullable();
            $table->text('device_id')->nullable();
            $table->text('os')->nullable();
            $table->longText('additional_info')->nullable();
            $table->text('push_token')->nullable();
            $table->rememberToken();
            $table->dateTime('login_at')->default(\Carbon\Carbon::now());
            $table->dateTime('logout_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id','cd_user_id')
                ->on('users')
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
        Schema::table('user_devices', function (Blueprint $table) {
            $table->dropForeign('cd_user_id');
            $table->drop();
        });
    }
}
