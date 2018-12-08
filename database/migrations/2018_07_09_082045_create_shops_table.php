<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateShopsTable.
 */
class CreateShopsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shops', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('access_token');
            $table->string('shop_name');
            $table->string('email');
            $table->string('owner');
            $table->string('plan')->nullable();
            $table->boolean('free_pass')->default(false);
            $table->dateTime('last_checked')->nullable();
            $table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('shops');
	}
}
