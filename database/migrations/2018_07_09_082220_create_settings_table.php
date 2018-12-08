<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateSettingsTable.
 */
class CreateSettingsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('settings', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('shop_id')->unsigned();
            $table->boolean('enabled')->default(1);
            $table->boolean('blocking_mode')->default(0);
            $table->boolean('eu_only')->default(1);
            $table->enum('position', ['top', 'bottom']);
            $table->enum('comply', ['browse', 'button']);
            $table->string('color_bg', 8)->nullable();
            $table->string('color_button', 8)->nullable();
            $table->timestamps();

            $table->foreign('shop_id', 'shop_id_forign_key')->references('id')->on('shops')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('settings');
	}
}
