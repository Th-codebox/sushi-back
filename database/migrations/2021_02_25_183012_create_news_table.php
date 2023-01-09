<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('promo_code_id')->nullable();
            $table->string('name', 255);
            $table->string('slug', 255);
            $table->string('sub_name', 255)->nullable();
            $table->string('image', 255)->nullable();
            $table->longText('description')->nullable();
            $table->longText('description_before_promo_code')->nullable();
            $table->longText('description_after_promo_code')->nullable();
            $table->text('h1')->nullable();
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->date('date')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('status')->default(0);

            $table->foreign('promo_code_id', 'n_promo_code_id')
                ->on('promo_codes')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');

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
        Schema::table('news', function (Blueprint $table) {
            $table->dropForeign('n_promo_code_id');

            $table->drop();

        });
    }
}
