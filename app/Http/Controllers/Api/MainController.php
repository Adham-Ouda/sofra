<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Restaurant;
use App\City;
use App\Category;
use App\Offer;
use App\Product;
use App\Contacts;
use App\Comment;
use App\PaymentMethod;
use App\Order;
use Log;


class MainController extends Controller
{
    public function restaurants(Request $request) {

      $restaurants= Restaurant::where(function ($query) use($request){
       if ($request->has('search')) {
       //search in restaurants table in name's column.
       $query->where('name','like', "%".$request->get('search')."%")
             ->orWhereHas('products',function ($q) use($request) { 
              //search in restaurants products. 
             $q->where('name','like', "%".$request->get('search')."%")
              //search which restaurants has a product with this name.
               ->orWhere('description','like',"%".$request->get('search')."%")
              //search which restaurants has a product with this description.
               ->orWhere('price','>=',$request->get('search'));
              //search which restaurants has a product with a price equal or greater than
             })
             ->orWhereHas('categories',function ($q2) use($request) {
              //search which restaurants has a category with this name.
             $q2->where('name','like', "%".$request->get('search')."%");
             });
                
          }	

       if ($request->has('city_id')) {

       $query->where('city_id', $request->city_id);

        }
       })->paginate(20);

       return responseJson(1,'success',$restaurants);      
    }

    public function cities() {
       
       $cities = City::all();

       return responseJson(1,'success',$cities);
    }

    public function categories() {
       
       $cities = Category::all();

       return responseJson(1,'success',$cities);
    }

    public function offers() {
       
       $offers = Offer::all();

       return responseJson(1,'success',$offers);
    }

    public function showOffer(Request $request) {
       
       //dd($request->id);
       $showOffer = Offer::where('id', $request->id)->first();


       return responseJson(1,'success',$showOffer);
    }

    public function products(Request $request) {
       
       $products = Product::where('restaurant_id', $request->restaurant_id)->first();

       return responseJson(1,'success',$products);
    }

    public function contacts(Request $request) {

    	$validator = validator()->make($request->all(), [
            
            'name' => 'required',
            'email' => 'required',
            'type' => 'required|in:complaint,suggestion,inquiry',
            'message' => 'required',
            'mobile' => 'required',

            ]);

      if ($validator->fails()) {
      
      return responseJson(0,$validator->errors()->first(),$validator->errors());	
            
             }

       
       $contacts = Contacts::create($request->all());
       $contacts->save();
       
       return responseJson(1,'added successfully',$contacts);     
    }

    public function comments() {
       
       $comments = Comment::all();

       return responseJson(1,'success',$comments);
    }

    public function restaurantInfo(Request $request) {
       
       $restaurantInfo = Restaurant::where('id', $request->id)->first();

       return responseJson(1,'success',$restaurantInfo);
    }

     public function paymentMethods() {
       
       $payment_methods = PaymentMethod::all();

       return responseJson(1,'success',$payment_methods);
    }

     /*public function testNotification(Request $request) {
      
      $audience = ['included_segments' => array('All')] ;

      if($request->has('ids')) {

        $audience = ['include_player_ids' => (array)$request->ids] ;
      }

      $contents = ['en' => $request->title ];
      
      log::info('test notification');
      log::info(json_encode($audience));

      $send = notifyByOneSignal($audience,$contents,$request->data);
       log::info($send);

      return response()->json([
        'status' => 1 ,
        'msg' => 'sended successfully' ,
        'send' => json_decode($send) ,

      ]);  

     } */

     public function testNotification(Request $request) {
      
        $tokens = $request->ids;
        //dd($tokens);
        $title = $request->title;
        //dd($title);
        $body = $request->body;
        //dd($body);
        $data = Order::first();
        //dd($data);
        $send = notifyByFirebase($title, $body, $tokens, $data, true);
        //dd($send);
        info("firebase result: " . $send);
 
        return response()->json([
            'status' => 1,
            'msg' => 'sended successfully',
            'send' => json_decode($send)
        ]);  

     }

}
