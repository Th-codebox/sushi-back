<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_id')->nullable();
            $table->string('name',255);
            $table->text('sub_title')->nullable();
            $table->string('slug',255);
            $table->string('ico',255)->nullable();

            $table->string('target',255);
            $table->longText('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('status')->default(0);

            $table->timestamps();
            $table->softDeletes();
            $table->foreign('category_id', 'collection_category_id_fk')
                ->on('categories')
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
        Schema::table('collections', function (Blueprint $table) {
            $table->dropForeign('collection_category_id_fk');
            $table->drop();
        });
    }
}
