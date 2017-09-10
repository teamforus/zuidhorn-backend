<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopKeepersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_keepers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->default('Unnamed');
            $table->integer('user_id')->unsigned();
            $table->string('iban')->nullable()->default(null);
            $table->string('kvk_number')->nullable()->default(null);
            $table->text('kvk_data')->nullable()->default(null);
            $table->string('phone_number')->nullable()->default(null);
            $table->string('website')->nullable()->default(null);
            $table->string('bussines_address')->nullable()->default(null);
            $table->string('state')->default('pending');
            $table->timestamps();

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
        Schema::dropIfExists('shop_keepers');
    }
}
