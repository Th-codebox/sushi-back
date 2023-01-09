<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class createCollectionTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collection_types', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('collection_id');
            $table->string('value');

            $table->foreign('collection_id', 'ct_collection_id')
                ->on('collections')
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
        Schema::table('collection_types', function (Blueprint $table) {
            $table->dropForeign('ct_collection_id');
            $table->drop();
        });
    }
}
