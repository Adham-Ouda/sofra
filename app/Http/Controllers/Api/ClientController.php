<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Order;
use App\Comment;
use App\Notification;
use App\Restaurant;

class ClientController extends Controller
{
    public function createOrder(Request $request) {

      /* $validator = validator()->make($request->all(), [
            
            'quantity' => 'required',
            //'total' => 'required',
            'notes' => 'required',
            'address' => 'required',
            'payment_method_id' => 'required',
            //'delivery_fee' => 'required',
            'order_price' => 'required',
            'restaurant_id' => 'required',

            ]);

      if ($validator->fails()) {
      
      return responseJson(0,$validator->errors()->first(),$validator->errors());	
            
             }

       //$createOrder = Order::create($request->all());
       $createOrder = new Order ;      
       $createOrder->status = null;
       $createOrder->delivery_fee = $order()->restaurant()->find($restaurant_id);
       //dd($createOrder->delivery_fee);
       $createOrder->total = $createOrder->order_price + $createOrder->delivery_fee ;
       $createOrder->commision = $createOrder->total * 0.1 ;
       dd($createOrder);
       $createOrder->save();
       
       return responseJson(1,'added successfully',$createOrder); */

       $validator = validator()->make($request->all(), [

        'restaurant_id' => 'required|exists:restaurants,id' ,
        'products.*.product_id' => 'required|exists:products,id',
        'products.*.quantity' => 'required',
        'address' => 'required',
        'payment_method_id' => 'required|exists:payment_methods,id' , 
       ]);

      
      if ($validator->fails()) {
      
      return responseJson(0,$validator->errors()->first(),$validator->errors());  
            
             }
         // object from the restaurant 
         $restaurant = Restaurant::find($request->restaurant_id);
         
         if($restaurant->status == 'closed'){
            return responseJson(0,'the restaurant is closed');
         }    

      // client order
      // set defaults of other parameters    
      $order = $request->user()->orders()->create([
         'restaurant_id' => $request->restaurant_id ,
         'special_order' => $request->special_order ,
         'notes' => $request->notes ,
         //'status' => null, // status has null as a default value in database
         'address' => $request->address,
         'payment_method_id' => $request->payment_method_id,

        ]); 

      //set default value for order_price    
      $order_price= 0 ;
      $delivery_fee = $restaurant->delivery_fee;

      foreach ($products as $p ) {
        // sended with the request product array for example
        // ['product_id' => 1 , 'quantity' => 2 , 'special_oreder' => 'no tomato' ]
        
        $product = Product::find($p['product_id']);

      $readyProduct = [
        
        $p['product_id'] => [
         'quantity' => $p['quantity'],
         'price' => $product->price ,
         'special_order' => (isset($p['special_order'])) ? $p['special_order'] : null ,
        ]  

        $order->products()->attach($readyProduct);

        $order_price = ($product->price * $p['quantity']);
      }

        // minium charge

        if ($order_price >= $restaurant->minium) {

          $total = $order_price + $delivery_fee ;
          $commision = 0.1 * $total ; // $commision = settings()->commision * $total; // settings() is a helper function
          $net = $total - $commision ;

          $update = $order->update([
            'order_price' => $order_price ,
            'delivery_fee' => $delivery_fee ,
            'total' => $total ,
            'commision' => $commision ,
            'net' => $net ,
            ]);

          $restaurant->notifications()->create([
            'title' => 'لديك طلب جديد' ,
            'title_en' => 'You have a new order' ,
            'content' => 'ليدك طلب جديد من العميل '.$request->user()->name ,
            'content_en' => 'You have a new order by client '.$request->user()->name ,
            'order_id' => $order->id , 
            ]); 

          //notifications

          $tokens = $restaurant->tokens()->where('token','!=','')->pluck('token')->toArray(); 
          $audience = ['include_player_ids' => $tokens];
          $contents = [
            'en' => 'You have a new order by client '.$request->user()->name,
            'ar' => 'لديك طلب جديد من العميل '.$request->user()->name, 
          ];  

          $send = notifyByOneSignal($audience, $contents, [
            'user_type' => 'restaurant',
            'action' => 'new_order',
            'order_id' => $order->id,

            ]);

          $send = json_decode($send);

          // end of notifications
          $data = [
           'order' => $order->fresh()->load('products') //
          ];

          return responseJson(1,'order created successfully',$data);

        }else{
          $order->products()->delete();
          $order->delete();
          return responseJson(0,'the order must be more than '.$restaurant->minium_charge.'$');
        }
       ];  
      }

    }

    public function myOrders(Request $request) {
       //dd($request->api_token);
    	$client = Auth::guard('client')->user();
       $myOrders = Order::where('status', $request->status)
                      ->where('client_id',$client->id)
                      ->get();
       //dd($myOrders);

       return responseJson(1,'success',$myOrders);
    }

    public function profile(Request $request) {
         
          $client = Auth::guard('client')->user();

          if ($request->has('name')) {
           $client->name= $request->input('name');
          }

          if ($request->has('email')) {
           $client->email= $request->input('email');
          }

           if ($request->has('home_description')) {
           $client->home_description= $request->input('home_description');
          }
           if ($request->has('mobile')) {
           $client->mobile= $request->input('mobile');
          }
          if ($request->has('password')) {

          	$validator = validator()->make($request->all(), [
            
            'password' => 'required|confirmed',
            ]);
            
            if ($validator->fails()) {

              return responseJson(0,$validator->errors()->first(),$validator->errors());	
            
             }

           $client->password= $request->input('password');
          }

          if ($request->has('quarter')) { 
           $client->quarter= $request->input('quarter');
          }
          
          if ($request->has('city_id')) {
           $client->city_id= $request->input('city_id');
          } 
          $client->save();

           return responseJson(1,'success',[
           
           'api_token' => $client->api_token ,
           'client' => $client

          ]);
          }

    public function showOrder(Request $request) {
       
       //dd($request->id);
       $showOrder = Order::where('id', $request->id)->first();

       return responseJson(1,'success',$showOrder);
    }      

    public function confirmOrder(Request $request) {

       $confirmOrder = $request->user()->orders()->find($request->order_id);

       if(isset($confirmOrder)){
       $confirmOrder->status = 'confirmed';
       $confirmOrder->save();
        return responseJson(1,'success',$confirmOrder);
       }else{
       return responseJson(0,'No order to confirm');
        }
    }

    public function review(Request $request) {

    $validator = validator()->make($request->all(), [
            
            'rate' => 'required',
            'body' => 'required',
            'restaurant_id' => 'required',


            ]);

      if ($validator->fails()) {
      
      return responseJson(0,$validator->errors()->first(),$validator->errors());	
            
             }

       $client = Auth::guard('client')->user();
       //$review = Comment::create($request->all());
       $review = new Comment;
       $review->rate = $request->input('rate');
       $review->body = $request->input('body');
       $review->name = $client->name;
       $review->client_id = $client->id;
       $review->restaurant_id = $request->input('restaurant_id');
       $review->save();
       //$restaurant->id = $review->restaurant();
       $restaurant = Restaurant::find($review->restaurant_id);//where('id',$request->restaurant_id)->first();
       $restaurant->rate = $review->where('restaurant_id',$request->restaurant_id)->avg('rate');/*->pluck('rate')*/ 
       //$rate = Comment::where('restaurant_id',$restaurant_id)->whereHas('restaurant',function $q ) 
       $restaurant->rate = round($restaurant->rate,1);                          
       //dd($restaurant->rate);
       
       $restaurant->save();
       return responseJson(1,'added successfully',$review,'restaurant rate = '.$restaurant->rate); 

    }	


    public function notifications(Request $request) {
    	$client = Auth::guard('client')->user();
       $notifications = Notification::where('notificationable_id',$client->id)
       ->where('notificationable_type','client')
       ->get();
       //dd($notifications);

       return responseJson(1,'success',$notifications);
    }

}
