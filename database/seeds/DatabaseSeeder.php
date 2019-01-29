<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

       /* Schema::table('comments', function (Blueprint $table){

           $table->string('name');
           $table->integer('client_id')->unsigned();
           $table->foreign('client_id')->references('id')->on('clients');
           $table->integer('restaurant_id')->unsigned();
           $table->foreign('restaurant_id')->references('id')->on('restaurants');
        }); */

        /* Schema::table('restaurants', function (Blueprint $table){

           $table->enum('payment', array('complaint', 'suggestion', 'inquiry'));
        });  */ 

      /*  Schema::table('payment_methods', function (Blueprint $table){

           $table->increments('id');
			$table->timestamps();
			$table->string('name', 255);
        }); */ 

        /*Schema::table('orders', function (Blueprint $table){

           //$table->integer('order_id')->unsigned();
           //$table->foreign('payment_method_id')->references('id')->on('payment_methods');
           $table->integer('restaurant_id')->unsigned();
           $table->integer('client_id')->unsigned();
        });*/

        
       /* Schema::table('offers', function (Blueprint $table){
              //$table->string('api_token', 60)->nullable();
               $table->integer('restaurant_id')->unsigned();
        }); */

       /* Schema::table('orders', function (Blueprint $table){

              $table->decimal('order_price');
        });  */  

       /* Schema::table('payments', function (Blueprint $table){

              $table->increments('id');
              $table->timestamps();
              $table->decimal('payment');
              $table->integer('restaurant_id');
              $table->integer('payment_method_id');
        }); */

        Schema::table('tokens', function (Blueprint $table){

              $table->increments('id');
              $table->timestamps();
              $table->string('token', 500);
              $table->string('platform', 255);
              $table->integer('tokenable_id');
              $table->string('tokenable_type', 255);
        }); 


    }
}
