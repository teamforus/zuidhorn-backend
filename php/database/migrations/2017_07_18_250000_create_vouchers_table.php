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
            $table->string('code');
            $table->integer('user_buget_id')->unsigned();
            $table->integer('shoper_id')->unsigned();
            $table->float('max_amount')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('user_buget_id')
            ->references('id')->on('user_bugets')
            ->onDelete('cascade');

            $table->foreign('shoper_id')
            ->references('id')->on('shopers')
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
