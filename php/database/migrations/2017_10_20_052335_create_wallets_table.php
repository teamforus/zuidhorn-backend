<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->increments('id');

            $table->text('public_key', 200);
            $table->text('private_key', 200);
            $table->text('address', 200);
            $table->text('passphrase', 200);

            // mediable details
            $table->integer('walletable_id')->unsigned()->nullable();
            $table->string('walletable_type');

            // timestamps
            $table->timestamps();

            // indexes
            $table->index(['walletable_id', 'walletable_type']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallets');
    }
}
