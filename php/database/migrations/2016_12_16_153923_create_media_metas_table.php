<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_metas', function($table)
        {
            // media meta index
            $table->increments('id')->index();

            // media id
            $table->integer('media_id')->unsigned()->index();

            // meta type
            $table->string('type')->nullable()->index();

            // meta details
            $table->string('value', 512)->default('');
            $table->string('path', 80)->default('');

            // foreign keys
            $table->foreign('media_id')->references('id')
            ->on('medias')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('media_metas');
    }
}
