<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSprintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sprints', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('posts');
            $table->dateTime('from');
            $table->dateTime('to');
            $table->json('filtration');
            $table->json('products');
            $table->json('details');
            $table->string('type')->default('manual');
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
        Schema::dropIfExists('sprints');
    }
}
