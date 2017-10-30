<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCitizenTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('citizen_tokens', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('citizen_id')->unsigned();
            $table->text('token')->bullable();

            $table->boolean('revoked')->default(0);
            $table->boolean('used_up')->default(0);

            $table->dateTime('expires_at')->nullable();
            
            $table->timestamps();

            // foreign keys
            $table->foreign('citizen_id')->references('id')
            ->on('citizens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('citizen_tokens');
    }
}
