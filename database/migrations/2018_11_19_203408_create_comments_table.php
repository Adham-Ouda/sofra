<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCommentsTable extends Migration {

	public function up()
	{
		Schema::create('comments', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('rate');
			$table->text('body');
			$table->string('name');
           $table->integer('client_id')->unsigned()->nullable();
           $table->foreign('client_id')->references('id')->on('clients');
           $table->integer('restaurant_id')->unsigned()->nullable();
           $table->foreign('restaurant_id')->references('id')->on('restaurants');
		});
	}

	public function down()
	{
		Schema::drop('comments');
	}
}