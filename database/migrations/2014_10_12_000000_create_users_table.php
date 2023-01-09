<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('role_id')->nullable();
            $table->string('name');
            $table->string('image')->nullable();
            $table->string('phone', 32)->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('status')->default(0);
            $table->rememberToken();
            $table->timestamp('last_visit_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('role_id', 'u_role_id')
                ->on('roles')
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('u_role_id');
            $table->drop();
        });

    }
}
