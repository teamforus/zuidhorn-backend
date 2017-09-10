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
            $table->string('public_key')->nullable();
            $table->string('private_key')->nullable();
            $table->integer('buget_id')->unsigned();
            $table->integer('user_id')->unsigned()->nullable();
            $table->float('amount')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('buget_id')
            ->references('id')->on('bugets')
            ->onDelete('cascade');

            $table->foreign('user_id')
            ->references('id')->on('users')
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
