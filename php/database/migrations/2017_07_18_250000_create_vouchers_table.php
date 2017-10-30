<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->nullable();
            $table->integer('budget_id')->unsigned();
            $table->integer('user_id')->unsigned()->nullable();
            $table->float('amount')->unsigned()->nullable();
            $table->timestamps();

            $table->string('activation_token')->nullable();
            $table->string('activation_email')->nullable();

            $table->foreign('budget_id')
            ->references('id')->on('budgets')
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
        Schema::dropIfExists('vouchers');
    }
}
