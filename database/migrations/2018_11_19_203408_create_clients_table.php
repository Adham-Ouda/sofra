<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClientsTable extends Migration {

	public function up()
	{
		Schema::create('clients', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name', 255);
			$table->string('email', 255);
			$table->text('home_description');
			$table->integer('city_id');
			$table->string('mobile');
			$table->string('password');
			$table->string('quarter', 255);
			$table->string('api_token', 60)->nullable();
		});
	}

	public function down()
	{
		Schema::drop('clients');
	}
}