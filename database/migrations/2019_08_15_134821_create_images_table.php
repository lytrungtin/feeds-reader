<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('channel_id');
            $table->string('url', 255);
            $table->string('title', 255);
            $table->unsignedBigInteger('link_id');
            $table->text('description')->nullable();
            $table->integer('width');
            $table->integer('height');
            $table->timestamps();
            $table->foreign('channel_id')->references('id')->on('channels')
                ->onDelete('cascade');
            $table->foreign('link_id')->references('id')->on('links');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('images');
    }
}
