<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTechnicalCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('technical_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->integer('weight')->nullable();
            $table->integer('proteins')->nullable();
            $table->integer('fats')->nullable();
            $table->integer('carbohydrates')->nullable();
            $table->integer('calories')->nullable();
            $table->longText('composition')->nullable();
            $table->longText('composition_for_cook')->nullable();
            $table->text('chef_comment')->nullable();
            $table->string('cooking_type')->nullable();
            $table->string('dish_type')->nullable();
            $table->string('manufacturer_type')->nullable();
            $table->integer('term_time')->nullable();
            $table->integer('time_to_cool')->nullable();
            $table->boolean('has_term')->default(0);
            $table->integer('cooking_time')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('status')->default(0);
            $table->softDeletes();
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
        Schema::dropIfExists('technical_cards');
    }
}
