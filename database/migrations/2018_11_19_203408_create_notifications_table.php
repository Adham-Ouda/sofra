<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNotificationsTable extends Migration {

	public function up()
	{
		Schema::create('notifications', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('title', 255);
			$table->text('body');
			$table->enum('action', array(''));
			$table->string('api_token', 60)->nullable();
			$table->integer('notificationable_id');
			$table->string('notificationable_type', 255);
		});
	}

	public function down()
	{
		Schema::drop('notifications');
	}
}