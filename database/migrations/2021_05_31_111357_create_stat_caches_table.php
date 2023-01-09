<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatCachesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stat_caches', function (Blueprint $table) {
            $table->id();
            $table->string('key','255');
                $table->string('utm','255');
                $table->string('group','255')->nullable();
                $table->longText('utm')->nullable();
                $table->boolean('json')->default(0);
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
        Schema::dropIfExists('stat_caches');
    }
}
