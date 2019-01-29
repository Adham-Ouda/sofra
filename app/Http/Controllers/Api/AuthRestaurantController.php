<?php

namespace App\Http\Controllers\Api;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Restaurant;


class AuthRestaurantController extends Controller
{
    public function register(Request $request) {
     
     $validator = validator()->make($request->all(), [
            
            'name' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'minium' => 'required',
            'whatsapp' => 'required',
            'delivery_fee' => 'required',
            'city_id' => 'required',
            'quarter' => 'required',
            'password' => 'required|confirmed',
            'image' => 'required',
            'status' => 'required',
            'rate' => 'required',

            ]);

      if ($validator->fails()) {
      
      return responseJson(0,$validator->errors()->first(),$validator->errors());	
            
             }

        $request->merge(['password' => bcrypt($request->password)]);
        $restaurant = Restaurant::create($request->all());
        $restaurant->api_token = str_random(60);
        $restaurant->save();
        //return responseJson(1,'added successfully',$restaurant);
        return responseJson(1,'added successfully',[
           
           'api_token' => $restaurant->api_token ,
           'restaurant' => $restaurant

        	]);     

  }

  public function login(Request $request) {

    	$validator = validator()->make($request->all(), [
            
            'email' => 'required',
            'password' => 'required',

            ]);
            
            if ($validator->fails()) {

           

              return responseJson(0,$validator->errors()->first(),$validator->errors());	
            
             }

            
             $restaurant = Restaurant::where('email',$request->email)->first();

             if($restaurant) {

             	if(Hash::check($request->password,$restaurant->password)) {

             		return responseJson(1,'Login successfully', [
                       
                      'api_token' => $restaurant->api_token ,
                      'restaurant' => $restaurant

             			]);
             	}else{

             		return responseJson(0,'Login failed');
             	}
             }else{

             	return responseJson(0,'Login failed');
             }
    
    }

    public function forgotPassword(Request $request){  

          $validator = validator()->make($request->all(), [
            
            'email' => 'required',

            ]);
            
            if ($validator->fails()) {

              return responseJson(0,$validator->errors()->first(),$validator->errors());  
            
             }

            $restaurant = Restaurant::where('email',$request->email)->first();
            if($restaurant){ 
              //$restaurant->pin_code =  str_random(6) ; 
              $restaurant->pin_code =  rand(111111,999999) ; // to generate pin code with numbers only
              $restaurant->save();
              return responseJson(1,'valid email',[
                      'pin_code' => $restaurant->pin_code
             			]);
             }else{

            return responseJson(0,'Invalid email');
          }

         } 

        public function resetPassword(Request $request){

          $validator = validator()->make($request->all(), [
            
            'pin_code' => 'required',
            'password' => 'required|confirmed'

            ]);
            
            if ($validator->fails()) {

              return responseJson(0,$validator->errors()->first(),$validator->errors());  
            
             }

            
            $restaurant = Restaurant::where('pin_code',$request->pin_code)->first();
            if($restaurant){ 
            $restaurant->password = bcrypt($request->password);
            $restaurant->save();

            return responseJson(1,'valid pin code');
             }else{

            return responseJson(0,'Invalid pin code');
            }
          }
     


}
