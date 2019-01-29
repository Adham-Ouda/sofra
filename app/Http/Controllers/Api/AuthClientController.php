<?php

namespace App\Http\Controllers\Api;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Client;

class AuthClientController extends Controller
{
    public function register(Request $request) {
     
     $validator = validator()->make($request->all(), [
            
            'name' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'home_description' => 'required',
            'city_id' => 'required',
            'quarter' => 'required',
            'password' => 'required|confirmed',

            ]);

      if ($validator->fails()) {
      
      return responseJson(0,$validator->errors()->first(),$validator->errors());	
            
             }

        $request->merge(['password' => bcrypt($request->password)]);
        $client = Client::create($request->all());
        $client->api_token = str_random(60);
        $client->save();
        //return responseJson(1,'added successfully',$client);
        return responseJson(1,'added successfully',[
           
           'api_token' => $client->api_token ,
           'client' => $client

        	]);     

  }  

  public function login(Request $request) {

    	$validator = validator()->make($request->all(), [
            
            'email' => 'required',
            'password' => 'required',

            ]);
            
            if ($validator->fails()) {

           // return responseJson(0,'validation error',$validator->errors());

              return responseJson(0,$validator->errors()->first(),$validator->errors());	
            
             }

            // return auth()->guard('api')->validate($request->all());
             $client = Client::where('email',$request->email)->first();

             if($client) {

             	if(Hash::check($request->password,$client->password)) {

             		return responseJson(1,'Login successfully', [
                       
                      'api_token' => $client->api_token ,
                      'client' => $client

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

            $client = Client::where('email',$request->email)->first();
            if($client){ 
              //$client->pin_code =  str_random(6) ; 
              $client->pin_code =  rand(111111,999999) ; // to generate pin code with numbers only
              $client->save();
              return responseJson(1,'valid email',[
                      'pin_code' => $client->pin_code

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

            
            $client = Client::where('pin_code',$request->pin_code)->first();
            if($client){ 
            $client->password = bcrypt($request->password);
            $client->save();

            return responseJson(1,'valid pin code'/*,$client->reset_code*/);
             }else{

            return responseJson(0,'Invalid pin code');
            }
          }

    public function registerToken(Request $request) {

          $validator = validator()->make($request->all(), [
            
            'platform' => 'required|in:android,ios',
            'token' => 'required',

            ]);
            
            if ($validator->fails()) {

              return responseJson(0,$validator->errors()->first(),$validator->errors());  
            
             }


          Token::where('token',$request->token)->delete(); 

          $request->user()->tokens()->create($request->all());

          return responseJson(1,'Registered successfully');

    }

    
    public function removeToken(Request $request) {

          $validator = validator()->make($request->all(), [
            
            'token' => 'required',

            ]);
            
            if ($validator->fails()) {

              return responseJson(0,$validator->errors()->first(),$validator->errors());  
            
             }


          Token::where('token',$request->token)->delete(); 

          return responseJson(1,'Deleted successfully');

    }
}

