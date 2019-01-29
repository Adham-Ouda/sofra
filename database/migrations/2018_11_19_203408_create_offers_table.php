<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOffersTable extends Migration {

	public function up()
	{
		Schema::create('offers', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name', 255);
			$table->string('price', 255);
			$table->text('description');
			$table->string('image', 255);
			$table->string('duration', 255);
		});
	}

	public function down()
	{
		Schema::drop('offers');
	}
}