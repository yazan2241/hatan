<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PhoneAuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\BankController;
use Illuminate\Support\Facades\DB;
use App\Models\Bank;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('login');
});
Route::get('/phone-auth', [PhoneAuthController::class, 'index']);

//Route::get('login' , function(){
//    return view('login');
//});

Route::post('/login' , function(Request $request){
    $phone =  $request->input('phone');
    $pass = $request->input('password');

    $user =  Bank::where('phone_number', '=', $phone)->where('password' , '=' , $pass)->get()->first();
    
    if(isset($user)){
        if($user->type == "1"){
            return redirect('/dashboard');
        }
        else{
            session(['id' => $user->id]);
            return redirect('/bank');
        }
    }
    
});

Route::get('/add' , function(){
    return view('addBank');
});
Route::get('/addAdmin' , function(){
    return view('addAdmin');
});
Route::post('/addAdminBank' , function(Request $request){
    $bank = new Bank();
    $data = $request->only($bank->getFillable());
    $bank->fill($data);
    $bank->password = $request->input('password');
    $bank->type = 1;
    if($request->file('bankImage')){
        $file = $request->file('bankImage');
        $filename = date('YmdHi').$file->getClientOriginalName();
        $file->move(public_path('public/images') , $filename);
        $bank['image'] = $filename;
    }
    $bank->save();
    return redirect('/dashboard');
});
Route::post('/addBank' , function(Request $request){
        $bank = new Bank();
        $data = $request->only($bank->getFillable());
        $bank->fill($data);
        $bank->password = $request->input('password');
        
        if($request->file('bankImage')){
            $file = $request->file('bankImage');
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('public/images') , $filename);
            $bank['image'] = $filename;
        }
        $bank->type = '0';
        $bank->save();
        return redirect('/dashboard');
});


Route::get('/editbank/{id}' , function($id){
    
    $bank = Bank::query()->where('id', '=', $id)->get();
    return view('editbank')->with('bank' , $bank);
});

Route::get('/dashboard' , [BankController::class , 'index3']);

Route::post('/editbank' , function(Request $request){
        $data = Bank::where('phone_number' , $request->phone_number)->first();
        
        $data->name = $request->has('name') ? $request->get('name') : $data->name;
        $data->phone_number = $request->has('phone_number') ? $request->get('phone_number') : $data->phone_number;
        $data->facebook_link = $request->has('facebook_link') ? $request->get('facebook_link') : $data->facebook_link;
        $data->instagrame_link = $request->has('instagrame_link') ? $request->get('instagrame_link') : $data->instagrame_link;
        $data->address_latitude = $request->has('address_latitude') ? $request->get('address_latitude') : $data->address_latitude;
        $data->address_longitude = $request->has('address_longitude') ? $request->get('address_longitude') : $data->address_longitude;
       
        if($request->file('bankImage')){
            $file = $request->file('bankImage');
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('public/images') , $filename);
            $data->image = $filename;
        }
        
        $data->update();
        return redirect('/dashboard');
    });


    Route::get('/delbank/{id}', function ($id) {
            $data = Bank::where('id', $id)->delete();
            return redirect('/dashboard');
        });



    Route::get("/bank" , function(){
        $id = session('id');
        
        $user = Bank::query()->where('id', '=', $id)->get();
        foreach($user as $u){
            $res = DB::select("select * from reservation where bank_phone = ? ORDER BY date DESC" , [$u->phone_number]);
            foreach($res as $r){
                $donor = DB::select("select * from donors where phone = ? limit 1" , [$r->donor_phone]);
                foreach($donor as $d)
                $r->name = $d->fullName;
            }
        }
        //foreach($res as $rr)
        //echo $rr;
      return view('bank' , ["user"=>$user , "res"=>$res]);
    });

    Route::post('/send-notification', [App\Http\Controllers\NotificationController::class, 'send']);


    