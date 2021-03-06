<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->integer('voucher_id')->unsigned();
            $table->integer('shop_keeper_id')->unsigned();
            $table->float('amount');
            $table->float('extra_amount');
            
            $table->integer('payment_id')->unsigned()->nullable();
            $table->integer('attempts')->unsigned()->default(0);
            $table->string('status')->default('pending');

            $table->timestamp('last_attempt_at')->nullable();
            $table->timestamps();

            $table->foreign('voucher_id')
            ->references('id')->on('vouchers')
            ->onDelete('cascade');

            $table->foreign('shop_keeper_id')
            ->references('id')->on('shop_keepers')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
