<?php 

 function responseJson($status,$message,$data=null,$data2=null) {

    	$response = [
    		'status' => $status ,
    		'message' => $message ,
    		'data' => $data ,
    		'data2' => $data2,
    	];

    	return response()->json($response);
    }

/* function notifyByOneSignal($audience = ['included_segments' => array('All')] , $contents = ['en' => ''] , $data = []){
    return true;
    // audience include_player_ids
    $appId = ['app_id' => env('ONE_SIGNAL_APP_ID')];
 
    $fields = json_encode((array)$appId + (array)$audience + ['contents' => (array)$contents ] + ['data' => (array)$data]);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
        'Authorization: Basic '.env('ONE_SIGNAL_KEY')));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
 
    $response = curl_exec($ch);
    curl_close($ch);
 
    return $response;
    }   */

 function notifyByFirebase($title, $body, $tokens, $data = [], $is_notification = true)
    {

    $registrationIDs = $tokens;
 
    $fcmMsg = array(
        'body' => $body,
        'title' => $title,
        'sound' => "default",
        'color' => "#203E78"
    );
    //dd($fcmMsg);
    $fcmFields = array(
        'registration_ids' => $registrationIDs,
        'priority' => 'high',
        'data' => $data
    );
    //dd($fcmFields);
    if ($is_notification)
    {
        $fcmFields['notification'] = $fcmMsg;
    }
    //dd($fcmFields['notification']);
 
    $headers = array(
        'Authorization: key=' . env('FIREBASE_API_ACCESS_KEY'),
        'Content-Type: application/json'
    );
    //dd($headers);
 
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmFields));
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}
