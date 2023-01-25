<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Event;
use App\Models\RegisterEvent;
use Illuminate\Support\Facades\Response;

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












/*
    Events Api
*/

Route::post('/addEvent' , function(Request $request){
    $event = new Event($request->all());
    if($event->save()){
        return Response::json(
            ['success' => 'Event added']
        , 200);
    }
    else{
        return Response::json(
            ['error' => 'Could not add event']
        , 404);
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
        $regEvent->certificateName = $event->certificateName;
        $regEvent->certificateImage = $event->certificateImage;
        $registeredEvents[$j] = $regEvent;
        $j = $j + 1;
    }

    return Response::json(
        $registeredEvents
    , 200);
    
});



Route::post('/joinEvent' , function(Request $request){
    set_time_limit(6000);
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
                
                $eveName = "شهادة حضور ";
                $eveName = $eveName . $event->name;
                $registeredUser->certificateName = $eveName;
                $registeredUser->certificateImage = generateCertificate($user->firstName . " " . $user->fatherName . " " .$user->lastName , $registeredUser->eventId , $user->idNumber , $event->name , $event->hours , $event->place , $event->date);
                
                
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


// Update 1.1


// Admin Api


Route::post('/getTeamMembers' , function(Request $request){
    $team = User::where('team' , '=' , $request->input('teamId'))->get();
    if($team){
        return Response::json(
            $team
        , 200);
    }
    else{
        return Response::json(
            ['error' => 'Team not found']
        , 404);
    }
});

Route::post('/getSectionMembers' , function(Request $request){
    $section = User::where('section' , '=' , $request->input('sectionId'))->get();
    if($section){
        return Response::json(
            $section
        , 200);
    }
    else{
        return Response::json(
            ['error' => 'Section not found']
        , 404);
    }
});




// Route::post('/test' , function(){
//     set_time_limit(6000);
//     return generateCertificate("محمد فارس الاحمد" , "2" ,"1234092" , "رعاية الأطفال الأيتام" , "4" , "دار السيد عبدالله عباس الشربتلي" , "12-12-2020");
// });



function generateCertificate($name , $eventId , $id , $event , $hours , $place , $date){

    

    require(public_path("arabic/Arabic.php"));
    $Arabic = new I18N_Arabic('Glyphs'); 

    $url = public_path("images/template.jpg");
    $img = imagecreatefromjpeg($url);

    $txt = "تشهد إدارة فريق هتان الثالث عشر التطوعي التابع";
    $img = writeText($txt , 350 , 200, $Arabic , $img , 0);

    $txt = " لجمعية البر بمحافظة جدة";
    $img = writeText($txt , 175 , 200 , $Arabic , $img , 0);

    $txt = " المتطوع / ";
    $img = writeText($txt , 450 , 240 , $Arabic , $img , 0);

    $txt = $name;
    $img = writeText($txt , 240 , 240 , $Arabic , $img , 1);

    $txt = " رقم الهوية / ";
    $img = writeText($txt , 420 , 280 , $Arabic , $img , 0);

    $txt = $id;
    $img = writeText($txt , 120 , 280 , $Arabic , $img , 1);

    $txt = " شارك وحضر فعالية ";
    $img = writeText($txt , 500 , 320 , $Arabic , $img , 0);

    $txt = " " . $event . " ";
    $img = writeText($txt , 340 , 320 , $Arabic , $img , 1);

    $txt = "بواقع عدد(" . $hours . ") ساعات تطوعية في  ";
    $img = writeText($txt , 410 , 360 , $Arabic , $img , 0);

    $txt = " " . $place . " ";
    $img = writeText($txt , 190 , 360 , $Arabic , $img , 1);

    $date = convert_date($date);
    $txt = " تاريخ " . $date;
    $img = writeText($txt , 320 , 400 , $Arabic , $img , 0);

    
    
    $quality = 100;
    
    imagejpeg($img, public_path("images/".$id."_".$eventId."_result.jpg"), $quality);

    return "images/".$id."_".$eventId."_result.jpg";
}

function convert_date($date){
    $dateList = explode("-" , $date);
    $res = $dateList[0] . " ";
    if($dateList[1] == 1) $res = $res . "كانون الثاني";
    if($dateList[1] == 2) $res = $res . "شباط";
    if($dateList[1] == 3) $res = $res . "اذار";
    if($dateList[1] == 4) $res = $res . "نيسان";
    if($dateList[1] == 5) $res = $res . "ايار";
    if($dateList[1] == 6) $res = $res . "حزيران";
    if($dateList[1] == 7) $res = $res . "تموز";
    if($dateList[1] == 8) $res = $res . "اب";
    if($dateList[1] == 9) $res = $res . "ايلول";
    if($dateList[1] == 10) $res = $res . "تشرين الاول";
    if($dateList[1] == 11) $res = $res . "تشرين الثاني";
    if($dateList[1] == 12) $res = $res . "كانون الاول";
    $res = $res . " ";
    $res = $res . $dateList[2];

    return $res;

}


function writeText($txt , $posX , $posY , $Arabic , $img , $type){
    // char 48
    // add 24
    // line 72

    $text = $Arabic->utf8Glyphs($txt);

    if($type == 0){
        for($i=0;$i<60-strlen($text);$i++){
            $text = $text . "      ";
        }
    }
    else{
        for($i=0;$i<60-strlen($text);$i++){
            $text = "      " . $text;
        }
    }
    
    $fontColor = imagecolorallocate($img , 30,30,30);

    
    $angle = 0;
    $fontSize = 14;

    $font_file = public_path("fonts/NotoNaskhArabic-VariableFont_wght.ttf");

    imagettftext($img , $fontSize , $angle , $posX , $posY , $fontColor ,$font_file, $text);
    return $img;

}