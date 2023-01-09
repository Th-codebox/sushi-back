<?php

use App\Enums\PolygonType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePolygonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('polygons', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('filial_id');

            $table->string('name');
            $table->polygon('area')->nullable();

            $table->integer('price')->nullable();
            $table->integer('free_from_price')->nullable();
            $table->time('time')->nullable();
            $table->string('type')->default(PolygonType::Green);
            $table->boolean('status')->default(0);
            $table->integer('sort_order')->default(0);

            $table->foreign('filial_id', 'pol_filial_id')
                ->on('filials')
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
        Schema::table('polygons', function (Blueprint $table) {
            $table->dropForeign('pol_filial_id');
            $table->drop();
        });
    }
}
