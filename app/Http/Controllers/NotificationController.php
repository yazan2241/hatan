<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bank;
use App\Classes\WhatsappAPI;
use App\Models\Donor;
use Illuminate\Support\Facades\DB;
class NotificationController extends Controller
{


  public function sendFCM($token , $msg) {
    // FCM API Url
    $url = 'https://fcm.googleapis.com/fcm/send';
  
    // Put your Server Key here
    $apiKey = "AAAAqEu3iqw:APA91bHRvadsirwQQ7nqVpoBIsnN_-E3GmF4VtOVSX5CgwXkeGg6QxVQ1hUXEuLB3zsj-24TmZBIuZIYQNS9yr4aHQ2xLiYM7XdTOfxCJt8POKOp7Iorj6drAJyoeOTiOfu1yTbO0eG-";
  
    // Compile headers in one variable
    $headers = array (
      'Authorization:key=' . $apiKey,
      'Content-Type:application/json'
    );
  
    // Add notification content to a variable for easy reference
    $notifData = [
      'title' => "Blood Bank",
      'body' => $msg,
      //  "image": "url-to-image",//Optional
      //'click_action' => "activities.NotifHandlerActivity" //Action/Activity - Optional
    ];
  
    $dataPayload = ['to'=> 'My Name', 
    'points'=>80, 
    'other_data' => 'This is extra payload'
    ];
  
    // Create the api body
    $apiBody = [
      'notification' => $notifData,
      //'data' => $dataPayload, //Optional
      'time_to_live' => 600, // optional - In Seconds
      //'to' => '/topics/mytargettopic'
      //'registration_ids' = ID ARRAY
      'to' => $token
    ];
  
    // Initialize curl with the prepared headers and body
    $ch = curl_init();
    curl_setopt ($ch, CURLOPT_URL, $url);
    curl_setopt ($ch, CURLOPT_POST, true);
    curl_setopt ($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt ($ch, CURLOPT_POSTFIELDS, json_encode($apiBody));
  
    // Execute call and save result
    $result = curl_exec($ch);
    echo "forn";
    echo $result;
    // Close curl after call
    curl_close($ch);
  
    //return $result;
      }


    public function send(Request $request)
    {
        $id = $request->input('id');
        $blood = $request->input('blood');
        $bb = "";
        for($i=strlen($blood)-1 ; $i>=0;$i--){
          $bb = $bb.$blood[$i];
        }
        $bank = Bank::query()->where('id', $id)->first();
        
        
        $b = $bank->name;

        //$donors = Donor::query()->where('bloodType', $blood)->get();
        $donors = DB::select("select * from donors where bloodType = '$bb'");

        //return $donors;
        
        foreach($donors as $donor){
            $token = $donor->token;
            $phone = $donor->phone;
            $msg = $b . " Blood Bank request blood supply of type " . $blood;
            echo "going in";
            $this->sendFCM($token , $msg);
            $this->sendMessage($phone , $msg);
        }
       
        return redirect('/bank');
    
      }

    public function sendMessage($number , $msg){
        
        $wp = new WhatsappAPI("2943", "91aa182b8dafa6c817126ce8d802bbc47d564ad3"); // create an object of the WhatsappAPI class with your user id and api key

        $number = $number; // NOTE: Phone Number should be with country code
        $message = $msg; // You can use WhatsApp Code to compose text messages like for new line = %0A, space = %20, *bold*, ~Strikethrough~, ```Monospace```

        $status = $wp->sendText($number, $message);

        $status = json_decode($status);

        if($status->status == 'error'){
            echo $status->response;
        }elseif($status->status == 'success'){
            echo 'Success <br />';
            echo $status->response;
        }else{
          print_r($status);
        }
      }

    public function sendNotification($device_token, $message)
    {
        $SERVER_API_KEY = 'AAAAqEu3iqw:APA91bHUgEgCJQR1Jha045samR5Tev_29stHBV7renU_ZzkgaQ5zVFWR0rx3ZahiftlUFqdnuxCAQUrmZ2femRGoss27xlHeP3dxvs9Qn9Vyo60m4-j0YAOw_kGCwuRtOHzkT7fByxkL';

        $data = [
          "to" => $device_token, // for single device id
          "data" => $message
      ];
        $dataString = json_encode($data);
    
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];
    
        $ch = curl_init();
      
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
               
        $response = curl_exec($ch);
      
        curl_close($ch);
      
        return $response;
    }


    

}
