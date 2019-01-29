<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Product;
use App\Restaurant;
use App\Payment;
use App\Notification;

class RestaurantController extends Controller
{
    public function profile(Request $request) {
       
       $restaurant = Auth::guard('restaurant')->user();

        if($request->has('name')) {
       	$restaurant->name = $request->input('name');
       }

        if ($request->has('email')) {
           $restaurant->email= $request->input('email');
          }

        if ($request->has('minium')) {
           $restaurant->minium= $request->input('minium');
          }
        if ($request->has('mobile')) {
           $restaurant->mobile= $request->input('mobile');
          }
        if ($request->has('password')) {

          	$validator = validator()->make($request->all(), [
            
            'password' => 'required|confirmed',
          ]);
            
            if ($validator->fails()) {

              return responseJson(0,$validator->errors()->first(),$validator->errors());	
            
             }

           $restaurant->password= $request->input('password');
          }

        if ($request->has('quarter')) { 
           $restaurant->quarter= $request->input('quarter');
          }
          
        if ($request->has('whatsapp')) {
           $restaurant->whatsapp= $request->input('whatsapp');
          } 

        if ($request->has('delivery_fee')) {
           $restaurant->delivery_fee= $request->input('delivery_fee');
          } 

        if ($request->has('status')) {
           $restaurant->status= $request->input('status');
          } 
          
        if ($request->has('city_id')) {
           $restaurant->city_id= $request->input('city_id');
          }

        if ($request->has('rate')) {
           $restaurant->rate= $request->input('rate');
          }
        
        if ($request->has('image')) { 
           $image = $request->file('image');
           $image_name = time().'.'.$image->getClientOriginalExtension();
           $destinationPath = public_path('/images');
           $image->move($destinationPath, $image_name);
           $restaurant->image = '/images/'.$image_name;
          }
                

          $restaurant->save();
          
          return responseJson(1,'success',[

            'api_token' => $restaurant->api_token,
            'restaurant' => $restaurant
          	]);
    } 

    public function myProducts(Request $request) {
       //dd($request->api_token);
       $restaurant = Auth::guard('restaurant')->user();
       $myProducts = Product::where('restaurant_id', $request->restaurant_id)->get();
       //dd($myProducts);

       return responseJson(1,'success',$myProducts);
    }

    public function addProduct(Request $request) {
    	//return $request->all();

    $validator = validator()->make($request->all(), [
            
            'name' => 'required',
            'price' => 'required',
            'description' => 'required',
            'image' => 'required',
            'duration' => 'required',

            ]);

      if ($validator->fails()) {
      
      return responseJson(0,$validator->errors()->first(),$validator->errors());	
            
             }
             //dd($request->price);

       $restaurant = Auth::guard('restaurant')->user();
       //$addProduct = Comment::create($request->all());
       $addProduct = Product::create($request->all());
       $addProduct->restaurant_id = $restaurant->id;
       // upload the image
       $image = $request->file('image');
       $image_name = time().'.'.$image->getClientOriginalExtension();
       $destinationPath = public_path('/images');
       $image->move($destinationPath, $image_name);
       $addProduct->image = '/images/'.$image_name;

       $addProduct->save();
       return responseJson(1,'added successfully',$addProduct); 

    }

    public function editProduct(Request $request) {
          

          $editProduct = $request->user()->products()->find($request->product_id);
          //$restaurant = Auth::guard('restaurant')->user();
          //$editProduct = Product::findOrFail($request->id);
          //$editProduct = Product::where('id',$request->id)->first();
          if(isset($editProduct)) {
          if ($request->has('name')) {
           $editProduct->name= $request->input('name');
          }

          if ($request->has('price')) {
           $editProduct->price= $request->input('price');
          }

           if ($request->has('description')) {
           $editProduct->description= $request->input('description');
          }
           if ($request->has('duration')) {
           $editProduct->duration= $request->input('duration');
          }

          if ($request->has('quarter')) { 
           $editProduct->quarter= $request->input('quarter');
          }
          
          if ($request->has('image')) {
           // upload the image
         $image = $request->file('image');
         $image_name = time().'.'.$image->getClientOriginalExtension();
         $destinationPath = public_path('/images');
         $image->move($destinationPath, $image_name);
         $editProduct->image = '/images/'.$image_name;
          } 

          //$editProduct->restaurant_id= $restaurant->id;

          $editProduct->save();

           return responseJson(1,'success',$editProduct);
            } else {
            	return responseJson(0,'No product to edit');
            }
         }

    public function deleteProduct(Request $request) {
          

          $deleteProduct = $request->user()->products()->find($request->product_id);

          if(isset($deleteProduct)) {
          	$deleteProduct->delete();
          return responseJson(1,'deleted');
            } else {
            	return responseJson(0,'No product to delete');
            }

         }

    public function myOrders(Request $request) {

       $validator = validator()->make($request->all(), [
         'status' => 'required|in:accepted,confirmed',
       	]);
       if ($validator->fails()) {

              return responseJson(0,$validator->errors()->first(),$validator->errors());	
             }
       $myOrders = $request->user()->orders()->where('status',$request->status)->get();
                      
       //dd($myOrders);
       return responseJson(1,'success',$myOrders);
    }          	

    public function showOrder(Request $request) {
      
      $showOrder = $request->user()->orders()->find($request->order_id);

      if(isset($showOrder)) {
      	return responseJson(1,'success',$showOrder);
      }else{
      	return responseJson(0,'No order to show');
      }
       
    }

    public function acceptOrder(Request $request) {

    	$acceptOrder = $request->user()->orders()->find($request->order_id);

    	if(isset($acceptOrder)) {
    	$acceptOrder->status = 'accepted';
    	$acceptOrder->save();
    	return responseJson(1,'success',$acceptOrder);
    }else{
        return responseJson(0,'No order to accept');
      }

    }

    public function rejectOrder(Request $request) {

    	$rejectOrder = $request->user()->orders()->find($request->order_id);

    	if(isset($rejectOrder)) {
    	$rejectOrder->status = 'rejected';
    	$rejectOrder->save();
    	return responseJson(1,'success',$rejectOrder);
    }else{
        return responseJson(0,'No order to accept');
      }

    }

    public function sendedOrder(Request $request) {

      $sendedOrder = $request->user()->orders()->find($request->order_id);

      if(isset($sendedOrder)) {
      $sendedOrder->status = 'sended';
      $sendedOrder->save();
      return responseJson(1,'success',$sendedOrder);
    }else{
        return responseJson(0,'No order to accept');
      }

    }


    //confirm that the client recieved the order
    public function confirmOrder(Request $request) {

    	$confirmOrder = $request->user()->orders()->find($request->order_id);

    	if(isset($confirmOrder)) {
    		$confirmOrder->status = 'received';
    		$confirmOrder->save();
    	return responseJson(1,'success',$confirmOrder);
    }else{
        return responseJson(0,'No order to confirm');
      }

    }

    public function myOffers(Request $request) {
      
     $myOffers = $request->user()->offers()->get();

     if(isset($myOffers)) {
     	return responseJson(1,'success',$myOffers);
    }else{
        return responseJson(0,'No order to confirm');
      }

    }
   
   public function newOffer(Request $request) {

   	 $validator = validator()->make($request->all(), [

        'name'=>'required',
        'price'=>'required',
        'description'=>'required',
        'image'=>'required',
        'duration'=>'required',

   	 	]); 
   	 if ($validator->fails()) {
   	 	return responseJson(0,$validator->errors()->first(),$validator->errors());
   	 }


     
     $newOffer = $request->user()->offers()->create($request->all());

     $image = $request->file('image');
     $image_name = time().'.'.$image->getClientOriginalExtension();
     $destinationPath = public_path('/images');
     $image->move($destinationPath, $image_name);
     $newOffer->image = '/images/'.$image_name;

     $newOffer->save();

     return responseJson(1,'success',$newOffer);
   }


   public function editOffer(Request $request) {

   	$editOffer = $request->user()->offers()->find($request->offer_id);

    if(isset($editOffer)) {
    
    if($request->has('name')) {
   	$editOffer->name = $request->input('name');
     }

    if ($request->has('price')) {
           $editOffer->price= $request->input('price');
          }

    if ($request->has('description')) {
           $editOffer->description= $request->input('description');
          }
    if ($request->has('duration')) {
           $editOffer->duration= $request->input('duration');
          }

    if ($request->has('image')) { 
           $image = $request->file('image');
           $image_name = time().'.'.$image->getClientOriginalExtension();
           $destinationPath = public_path('/images');
           $image->move($destinationPath, $image_name);
           $editOffer->image = '/images/'.$image_name;
          }

          $editOffer->save();

        return responseJson(1,'success',$editOffer);
           } else {
        return responseJson(0,'No Offer to edit');
           }
    

   }

   public function deleteOffer(Request $request) {
          

          $deleteOffer = $request->user()->offers()->find($request->offer_id);

          if(isset($deleteOffer)) {
          	$deleteOffer->delete();
          return responseJson(1,'deleted');
            } else {
            	return responseJson(0,'No product to delete');
            }

         }

    public function changeStatus(Request $request) {

    	$restaurant = Auth::guard('restaurant')->user();
      //dd($restaurant);
      $changeStatus = $restaurant->status ;
    	if($changeStatus == 'opened') {
        $restaurant->status = 'closed';
        $restaurant->save();

      }elseif($changeStatus == 'closed'){
        $restaurant->status = 'opened';
        $restaurant->save();

      }
      return responseJson(1,'success',$restaurant);
    }     

    
    public function getCommissions(Request $request) {
    
    $getCommissions = $request->user()->payments()->find($request->api_token);


    }

    public function notifications(Request $request) {
      //dd($request->user()->id);
     /*$notifications = $request->user()->notifications()->where('notificationable_id',$request->user()->id)
                                                       ->where('notificationable_type','restaurant')
                                                       ->get();
                                                       dd($notifications); */

       $restaurant = Auth::guard('restaurant')->user();
       $notifications = Notification::where('notificationable_id',$restaurant->id)
       ->where('notificationable_type','restaurant')
       ->get();                                               

       return responseJson(1,'success',$notifications);
    }
}
