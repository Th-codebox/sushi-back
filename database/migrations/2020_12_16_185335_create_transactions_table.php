<?php

use App\Enums\TransactionStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        Schema::create('transactions', function (Blueprint $table) {

            $table->increments('id');

            $table->unsignedInteger('sender_id')->nullable();
            $table->unsignedInteger('operator_id')->nullable();

            $table->unsignedInteger('order_id')->nullable();


            $table->dateTime('date')->useCurrent();
            $table->string('transit_type');
            $table->string('operation_type');
            $table->string('payment_type')->nullable();
            $table->string('status')->default(TransactionStatus::New);
            $table->integer('balance_before')->nullable();
            $table->integer('balance_after')->nullable();
            $table->integer('price')->nullable();

            $table->integer('quantity_checks')->nullable();


            $table->foreign('sender_id', 't_s_id')
                ->on('users')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('operator_id', 't_o_id')
                ->on('users')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('order_id', 't_co_id')
                ->on('orders')
                ->references('id')
                ->onUpdate('CASCADE')
                ->onDelete('SET NULL');



            $table->dateTime('archived_at')->nullable();

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
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign('t_s_id');
            $table->dropForeign('t_o_id');
            $table->dropForeign('t_co_id');
            $table->drop();
        });
    }
}
