<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('link_id')->nullable();
            $table->unsignedBigInteger('docs_id')->nullable();
            $table->unsignedBigInteger('language_id')->nullable();
            $table->unsignedBigInteger('managing_editor_id')->nullable();
            $table->unsignedBigInteger('web_master_id')->nullable();
            $table->unsignedBigInteger('copyright_id')->nullable();
            $table->unsignedBigInteger('generator_id')->nullable();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->timestamp('last_build_date')->nullable();
            $table->timestamp('publish_date')->nullable();
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('link_id')->references('id')->on('links');
            $table->foreign('docs_id')->references('id')->on('docs');
            $table->foreign('language_id')->references('id')->on('languages');
            $table->foreign('managing_editor_id')->references('id')->on('people');
            $table->foreign('web_master_id')->references('id')->on('people');
            $table->foreign('copyright_id')->references('id')->on('copyrights');
            $table->foreign('generator_id')->references('id')->on('generators');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('channels');
    }
}
