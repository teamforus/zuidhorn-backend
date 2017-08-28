<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopKeeperDeviceTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_keeper_device_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token', 255)->default('');
            $table->boolean('used')->default(1);
            $table->integer('shop_keeper_id')->unsigned();
            $table->timestamps();

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
        Schema::dropIfExists('shop_keeper_device_tokens');
    }
}
