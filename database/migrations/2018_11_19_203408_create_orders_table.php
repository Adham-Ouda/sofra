<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrdersTable extends Migration {

	public function up()
	{
		Schema::create('orders', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->decimal('total');
			$table->text('notes');
			$table->string('address', 255);
			$table->string('payment', 255);
			$table->decimal('delivery_fee');
			$table->decimal('commission');
			$table->decimal('order_price');
			$table->enum('status', array('accepted', 'rejected', 'delivered', 'received'));
		});
	}

	public function down()
	{
		Schema::drop('orders');
	}
}