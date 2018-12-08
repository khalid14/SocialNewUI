<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSinglePostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('single_post', function (Blueprint $table) {
            $table->increments('id');
            $table->string('message');
            $table->integer('image');
            $table->json('social');
            $table->json('hash_tags');
            $table->boolean('status')->default(false);
            $table->unsignedInteger('shop_id');
            $table->timestamps();

			$table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('single_post');
    }
}
