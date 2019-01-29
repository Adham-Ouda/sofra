<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateContactsTable extends Migration {

	public function up()
	{
		Schema::create('contacts', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name', 255);
			$table->string('email', 255);
			$table->enum('type', array('complaint', 'suggestion', 'inquiry'));
			$table->text('message');
			$table->string('mobile', 255);
			$table->string('api_token', 60)->nullable();
		});
	}

	public function down()
	{
		Schema::drop('contacts');
	}
}