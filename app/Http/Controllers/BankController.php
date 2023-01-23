<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $banks = Bank::query()->where('name', 'LIKE', "%{$search}%")->get();
        //if($banks->isNotEmpty()) return $banks;
        //else return 'No Result Found';
        return $banks;
    }
    public function index2(Request $request)
    {
        //$search = $request->input('search');
        //$banks = Bank::query()->where('name', 'LIKE', "%{$search}%")->get();
        $banks = Bank::all()->last();
        //if($banks->isNotEmpty()) return $banks;
        //else return 'No Result Found';
        return $banks;
    }
    public function index1(Request $request)
    {
        /*
        $validator = Validator::make($request->all(), [
            'latitude' => 'required',
            'longitude' => 'required',
        ]);
    
        if ($validator->fails()) {
            return Response::json([
                'message' => 'Error Input Data',
                'data' => $validator->getMessageBag()
            ], 400);
        }
        */
        if($request->has('search')) $search = $request->input('search');
        else $search = '';
        $lat = $request->input('latitude');
        $long = $request->input('longitude');
        
        $banks = Bank::query()->where('name', 'LIKE', "%{$search}%")->get();

        foreach($banks as $bank){

            $longi1 = deg2rad($long); 
            $longi2 = deg2rad($bank->address_longitude); 
            $lati1 = deg2rad($lat); 
            $lati2 = deg2rad($bank->address_latitude); 
           
            //Haversine Formula 
            $difflong = $longi2 - $longi1; 
            $difflat = $lati2 - $lati1; 
            
            $earthRadius = 6371000;

            $angle = 2 * asin(sqrt(pow(sin($difflat / 2), 2) +
            cos($lati1) * cos($lati2) * pow(sin($difflong / 2), 2)));
            //$answer = $angle * $earthRadius;

            $val = pow(sin($difflat/2),2)+cos($lati1)*cos($lati2)*pow(sin($difflong/2),2); 
      
            $res2 =6378.8 * (2 * asin(sqrt($val))); //for kilometers


            
            $a = pow(cos($lati2) * sin($difflong), 2) +
                pow(cos($lati1) * sin($lati2) - sin($lati1) * cos($lati2) * cos($difflong), 2);
            $b = sin($lati1) * sin($lati2) + cos($lati1) * cos($lati2) * cos($difflong);

            $angle = atan2(sqrt($a), $b);
            $answer = $angle * $earthRadius;
            if($answer >= 1000){
                $answer = $answer / 1000;
                $bank->distance = number_format($answer,0,'.','') . " KM";
            }
            else{
                $bank->distance = number_format($answer,0,'.','') . " M";
            }
            //$arr = array('distance' => $res2) ;// features

            //echo json_encode($arr);//$data[] = $_POST['data'];
    
            //$merge = array_merge($bank, $arr);
            //$jsonData = json_encode($merge);
            
        }
        //if($banks->isNotEmpty()) return $banks;
        //else return 'No Result Found';
        if($banks != null){
            return Response::json(
                $banks
            , 200);
        } else {
            return Response::json(
                []
            , 404);
        }
    }


    public function index3(Request $request)
    {

        $banks = Bank::all()->where('type' , '0');
        if($banks != null){
            return view('dashboard')->with('banks',$banks);
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $bank = new Bank();
        $data = $request->only($bank->getFillable());
        $bank->fill($data);

        if($request->file('bankImage')){
            $file = $request->file('bankImage');
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('public/images') , $filename);
            $bank['image'] = $filename;
        }
        $bank->save();
        //redirect()->route('banks');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $id = $request->input('id');
        /*
        $validator = Validator::make(["id" => $id], [
            'id' => 'required',
        ]);
    
        if ($validator->fails()) {
            return Response::json([
                'message' => 'Error Input Data',
                'data' => $validator->getMessageBag()
            ], 400);
        }
        */
        $bank = Bank::find($id);
        if($bank != null){
            return Response::json(
                $bank
             , 200);
        } else {
            return Response::json(
                []
             , 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function edit(Bank $bank)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bank $bank)
    {
        if($request->file('bankImage')){
            $file = $request->file('bankImage');
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('public/images') , $filename);
            $bank['image'] = $filename;
        }
        $bank->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bank $bank)
    {
        //
    }
}
