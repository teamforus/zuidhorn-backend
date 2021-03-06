<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfficesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shop_keeper_id')->unsigned();
            $table->string('address', 255)->default('');
            $table->string('phone', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('lon', 100)->nullable();
            $table->string('lat', 100)->nullable();
            $table->boolean('parsed')->default(0);
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
        Schema::dropIfExists('offices');
    }
}
