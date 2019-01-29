<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderProductTable extends Migration {

	public function up()
	{
		Schema::create('order_product', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->integer('order_id');
			$table->integer('product_id');
			$table->integer('quantity');
			$table->decimal('price');
			$table->string('special_order', 255);
		});
	}

	public function down()
	{
		Schema::drop('order_product');
	}
}