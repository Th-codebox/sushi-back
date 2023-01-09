<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('group');
            $table->string('namespace');
            $table->string('controller');
            $table->string('method');
            $table->integer('sort_order')->default(0);
            $table->boolean('status')->default(0);
            $table->timestamps();

            $table->unique(['method', 'controller'],'mc_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permissions', function (Blueprint $table) {
            $table->drop();
        });
    }
}
