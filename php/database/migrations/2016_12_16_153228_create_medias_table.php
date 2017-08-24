<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medias', function($table)
        {
            // media index
            $table->increments('id')->index();

            // mediable details
            $table->integer('mediable_id')->unsigned()->nullable();
            $table->string('mediable_type');

            // media type
            $table->string('type')->index();
            
            // media details
            $table->string('name', 255)->default('');
            $table->text('description');
            $table->string('ext', 10);
            $table->boolean('wide')->default(0);
            $table->boolean('confirmed', FALSE);

            // timestamps
            $table->timestamps();

            // indexes
            $table->index(['mediable_id', 'mediable_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medias');
    }
}
