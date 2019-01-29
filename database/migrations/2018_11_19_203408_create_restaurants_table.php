<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRestaurantsTable extends Migration {

	public function up()
	{
		Schema::create('restaurants', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name', 255);
			$table->string('email', 255);
			$table->decimal('minium');
			$table->string('mobile', 255);
			$table->string('whatsapp', 255);
			$table->decimal('delivery_fee');
			$table->string('password', 255);
			$table->string('image', 255);
			$table->boolean('status');
			$table->string('quarter', 255);
			$table->integer('city_id');
			$table->integer('rate');
		});
	}

	public function down()
	{
		Schema::drop('restaurants');
	}
}