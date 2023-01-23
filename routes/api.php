<?php

use App\Http\Controllers\BankController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DonorController;
use App\Models\Bank;
use App\Models\Donor;
use App\Models\User;
use App\Models\Event;
use App\Models\RegisterEvent;
use GrahamCampbell\ResultType\Success;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login' , function(Request $request){

    $input = $request->all();
    
    $email =  $request->input('email');
    $password = $request->input('password');
    $user =  User::where('email', '=', $email)->first();
    if($user){
    if($user->password == $password){
            return Response::json(
                $user
            , 200);
        } else {
            return Response::json(['error' => 'User not found'], 404);
        }
    }
    else {
        return Response::json(['error' => 'User not found'], 404);
    }
});

Route::post('/register' , function (Request $request){

    $user =  User::where('email', '=', $request->input('email'))->first();
    if($user){
        return Response::json(['error' => 'email already exist'], 201);
    }
    else{
        $user = new User($request->all());
        $choosenTeam = 1;

        $allTeams = User::all();
        $allTeamsSize = sizeof($allTeams);

        $choosenTeam = ceil($allTeamsSize/ 80);
        
        $user->team = $choosenTeam;
        $user->hours = 0;

        if($user->save()){

        return Response::json(
            $user
        , 200);
        }else{
            return Response::json(['error' => 'could not create the user'], 404);
        }
    }

});


Route::post('/chooseSection' , function (Request $request){


    $user =  User::where('id', '=', $request->input('id'))->first();
    if($user){
        if($request->has('section')){
            $section = $request->input('section');
            $user->section = $section;
        }

        if($user->update()){
            return Response::json(
                ['status' => '1']
            , 200);
        }else{
            return Response::json(['status' => '0'], 404);
        }
    }
    else{
        return Response::json(['error' => 'user not exist'], 404);
    }
});


// TODO
Route::post('/home' , function (Request $request){


    $user =  User::where('id', '=', $request->input('id'))->first();
    if($user){
        $events = RegisterEvent::where('userId' , '=' , $request->input('id'))->get();
        $user->eventsCount = sizeof($events);
        return Response::json(
            $user
        , 200);
    }
    else{
        return Response::json(['error' => 'user not exist'], 404);
    }
});



Route::post('/profile' , function(Request $request){
    $user =  User::where('id', '=', $request->input('id'))->first();
    if($user){
        return Response::json(
            $user
        , 200);
    } else {
        return Response::json(['error' => 'User not found'], 404);
    }
});


Route::post('/eventProfile' , function(Request $request){
    $event =  Event::where('id', '=', $request->input('eventId'))->first();
    if($event){
        return Response::json(
            $event
        , 200);
    } else {
        return Response::json(['error' => 'Event not found'], 404);
    }
});

Route::post('/joinEvent' , function(Request $request){
    $user =  User::where('id', '=', $request->input('id'))->first();
    if($user){
        $event =  Event::where('id', '=', $request->input('eventId'))->first();
        if($event){
            $isRegistered = RegisterEvent::where('userId' , '=' , $request->input('id'))->where('eventId' , '=' , $request->input('eventId'))->first();
            if($isRegistered){
                return Response::json(['error' => 'Already registered'], 201);
            }
            else{
                $registeredUser = new RegisterEvent();
                $registeredUser->userId = $request->input('id');
                $registeredUser->eventId = $request->input('eventId');
                
                $user->hours = $user->hours + $event->hours;
                $user->update();
                $registeredUser->save();

                return Response::json(
                    ['success' => 'Event Registerd']
                , 200);
                }
        } else {
            return Response::json(['error' => 'Event not found'], 404);
        }
    }
    else{
        return Response::json(['error' => 'User not found'], 404);
    }
    
});


Route::get('/getEvents' , function(Request $request){
    
    $events = Event::all();

    return Response::json(
        $events
    , 200);
    
});


Route::post('/getRegisteredEvents' , function(Request $request){
    
    $events = RegisterEvent::where('userId' , '=' , $request->input('id'))->get();
    $registeredEvents = array();
    $j = 0;
    
    foreach($events as $event){
        $regEvent = Event::where('id' , '=' , $event->eventId)->first();
        $registeredEvents[$j] = $regEvent;
        $j = $j + 1;
    }

    return Response::json(
        $registeredEvents
    , 200);
    
});

Route::post('/cancelRegisteredEvent' , function(Request $request){
    
    $events = RegisterEvent::where('userId' , '=' , $request->input('userId'))->where('eventId' , '=' , $request->input('eventId'))->delete();
    
    if($events){
        return Response::json(
            ['success' => 'event cancelled']
        , 200);
    }
    else{
        return Response::json(
            ['error' => 'event not found']
        , 404);
    }
    
});


































Route::post('/blogin' , function(Request $request){

    $input = $request->all();
    
    $phone =  $request->input('phone');
    $token = $request->input('token');
    $user =  Donor::where('phone', '=', $phone)->first();
    if($user){
            $user->token = $request->has('token') ? $request->get('token') : $user->token;
            $user->update();
            return Response::json(
                $user
            , 200);
        } else {
            return Response::json([], 404);
        }
});



Route::post('/register1' , function (Request $request){
    /*
    $validator = Validator::make($request->all(), [
        'fullName' => 'required',
        'phone' => 'required|unique:donors,phone',
        'address' => 'required',
        'age' => 'required|numeric',
        'gender' => 'required|string',
        'weight' => 'required',
        'height' => 'required',
        'medicalHistory' => 'required',
        'bloodType' => 'required',
        'token' => 'required'
    ]);

    if ($validator->fails()) {
        return Response::json([
            'message' => 'Error Input Data',
            'data' => $validator->getMessageBag()
        ], 400);
    }
    */
    $donor = new Donor();
    $donor->fullName = $request->input('fullName');
    $donor->phone = $request->input('phone');
    $donor->address = $request->input('address');
    $donor->age = $request->input('age');
    $donor->gender = $request->input('gender');
    $donor->bloodType = $request->input('bloodType');
    $donor->weight = $request->input('weight');
    $donor->height = $request->input('height');
    $donor->medicalHistory = $request->input('medicalHistory');
    $donor->medicalHistory = $request->input('medicalHistory');
    $donor->token = $request->token;
    $donor->save();

    return Response::json(
        $donor
    , 200);

});


Route::post('/check' , function (Request $request){
    /*
    $validator = Validator::make($request->all(), [
        'fullName' => 'required',
        'phone' => 'required|unique:donors,phone',
        'address' => 'required',
        'age' => 'required|numeric',
        'gender' => 'required|string',
        'weight' => 'required',
        'height' => 'required',
        'medicalHistory' => 'required',
        'bloodType' => 'required',
        'token' => 'required'
    ]);

    if ($validator->fails()) {
        return Response::json([
            'message' => 'Error Input Data',
            'data' => $validator->getMessageBag()
        ], 400);
    }
    */
    $phone =  $request->input('phone');

    $user =  Donor::where('phone', '=', $phone)->first();
    if($user){
            return Response::json(
                'User exist'
            , 301);
        } else {
            return Response::json('User not exist', 200);
        }

});

Route::post('/donor-profile' , function(Request $request){
    /*
    $validator = Validator::make($request->all() , [
        'phone' => 'required'
    ]);

    if ($validator->fails()) {
        return Response::json([
            'message' => 'Error Input Data',
            'data' => $validator->getMessageBag()
        ], 400);
    }
    */
    $data = Donor::where('phone' , $request->phone)->first();
    
    return Response::json(
        $data
    , 200);
});

Route::post('/donor-update' , function(Request $request){
/*
    $validator = Validator::make($request->all() , [
        'phone' => 'required'
    ]);

    if ($validator->fails()) {
        return Response::json([
            'message' => 'Error Input Data',
            'data' => $validator->getMessageBag()
        ], 400);
    }
*/
    $data = Donor::where('phone' , $request->phone)->first();
    
    $data->fullName = $request->has('fullName') ? $request->get('fullName') : $data->fullName;
    $data->phone = $request->has('phone') ? $request->get('phone') : $data->phone;
    $data->address = $request->has('address') ? $request->get('address') : $data->address;
    $data->age = $request->has('age') ? $request->get('age') : $data->age;
    $data->gender = $request->has('gender') ? $request->get('gender') : $data->gender;
    $data->weight = $request->has('weight') ? $request->get('weight') : $data->weight;
    $data->height = $request->has('height') ? $request->get('height') : $data->height;
    $data->medicalHistory = $request->has('medicalHistory') ? $request->get('medicalHistory') : $data->medicalHistory;
    $data->bloodType = $request->has('bloodType') ? $request->get('bloodType') : $data->bloodType;
    $data->token = $request->has('token') ? $request->get('token') : $data->token;
    
    $data->update();

    return Response::json(
        $data
    , 200);
});

Route::get('/bloodBank' , function(){
    return view('bloodBank');
});

Route::post('/bloodBank-details' , [BankController::class , 'show']);

//Route::get('/allBloodBanks' , [BankController::class , 'index']);

Route::post('/allBanks' , [BankController::class , 'index1']);



// Dashboard

Route::get('/donors' , [DonorController::class , 'index']);

Route::post('/bloodBank' , [BankController::class , 'store']);


Route::post('/donor-delete', function (Request $request) {
/*
    $validator = Validator::make($request->all(), [
        'id' => 'required'
    ]);

    if ($validator->fails()) {
        return Response::json([
            'message' => 'Error Input Data',
            'data' => $validator->getMessageBag()
        ], 400);
    }
*/
    $phone =  $request->input('phone');
    $data = Donor::where('phone', $phone)->delete();

    return Response::json(
        $data
    , 200);

});

Route::post('/bank-delete', function (Request $request) {
    /*
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);
    
        if ($validator->fails()) {
            return Response::json([
                'message' => 'Error Input Data',
                'data' => $validator->getMessageBag()
            ], 400);
        }
    */
        $phone =  $request->input('phone');
        $data = Bank::where('phone_number', $phone)->delete();
    
        return Response::json(
            $data
        , 200);
    
    });


// Notification

Route::post('send-notification', [App\Http\Controllers\NotificationController::class, 'send']);

Route::get('/wapi' , [App\Http\Controllers\PhoneAuthController::class , 'sendMessage']);


Route::post('/bank-update' , function(Request $request){
        $data = Bank::where('phone_number' , $request->phone_number)->first();
        
        $data->name = $request->has('name') ? $request->get('name') : $data->name;
        $data->phone_number = $request->has('phone_number') ? $request->get('phone_number') : $data->phone_number;
        $data->address = $request->has('address') ? $request->get('address') : $data->address;
        $data->address_latitude = $request->has('address_latitude') ? $request->get('address_latitude') : $data->address_latitude;
        $data->address_longitude = $request->has('address_longitude') ? $request->get('address_longitude') : $data->address_longitude;
        $data->facebook_link = $request->has('facebook_link') ? $request->get('facebook_link') : $data->facebook_link;
        $data->instagrame_link = $request->has('instagrame_link') ? $request->get('instagrame_link') : $data->instagrame_link;
        
        $data->update();
    
        return Response::json(
            $data
        , 200);
    });

Route::post('/reserve' , function(Request $request){
    $phone =  $request->input('phone');
    $date = $request->input('date');
    $bank_phone = $request->input('bank_phone');

    $d = new DateTime($date);
    $currentDate = new DateTime();
    $d = $d->format('Y-m-d H');
    $currentDate = $currentDate->format('Y-m-d H');
    $err = [];
    $err['error'] = "Date reserved , pick another date";
    
    $query = DB::select("select * from reservation where bank_phone = ? and date = ?" , [$bank_phone , $d]);
    if($query || $d < $currentDate){
        return Response::json(
            $err
        , 404);
    } else {
        $query = DB::insert("insert into reservation(bank_phone , donor_phone , date) values (? , ? , ?)" , [$bank_phone , $phone , $d]);
        if($query){
            return Response::json(
                $query
            , 200);
        } else {
            return Response::json([], 404);
        }
    }

    
});

Route::post('/myreservation' , function(Request $request){
    $phone =  $request->input('phone');
    $query = DB::select("select * from reservation where donor_phone = ?" , [$phone]);
    foreach($query as $q){
        $q->date = $q->date.":0:0";
        
        $d = new DateTime($q->date);
        $currentDate = new DateTime();

        $d = $d->format('Y-m-d H');
        $currentDate = $currentDate->format('Y-m-d H');

        if($q < $currentDate){
            unset($q);
        }
        $qu = DB::select("select * from banks where phone_number = ? limit 1" , [$q->bank_phone]);
        foreach($qu as $qu){
            $q->name = $qu->name;
            $q->image = $qu->image;
            $q->phone_number = $qu->phone_number;
            $q->address = $qu->address;
        }
    }
    
    if($query){
        return Response::json(
            $query
        , 200);
    } else {
        return Response::json([], 404);
    }
});

Route::post('/checkavailable' , function(Request $request){
    $date = $request->input('date');
    $bank_phone = $request->input('bank_phone');
    $query = DB::select("select * from reservation where bank_phone = ? and date = ?" , [$bank_phone , $date]);
    if($query){
        return Response::json(
            "True"
        , 200);
    } else {
        return Response::json("False", 200);
    }
});