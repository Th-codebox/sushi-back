<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('referral_promo_code',15);
            $table->date('birthday')->nullable();
            $table->string('name',255)->nullable();
            $table->text('utm')->nullable();
            $table->string('phone', 32)->nullable()->unique();
            $table->string('email',255)->nullable();
            $table->string('code',255)->nullable();

            $table->rememberToken();
            $table->boolean('status')->default(0);
            $table->boolean('confirm_phone')->default(0);
            $table->timestamp('code_last_send_at')->nullable();
            $table->timestamp('last_visit_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
