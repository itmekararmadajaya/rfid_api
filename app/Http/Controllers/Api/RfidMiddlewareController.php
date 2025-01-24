<?php

namespace App\Http\Controllers\Api;

use App\Events\RfidMiddlewareEvent;
use App\Http\Controllers\Controller;
use App\Models\RfidData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RfidMiddlewareController extends Controller
{
    public function list(){
        // try {
        //     $message = [
        //         'client_type' => 112,
        //         'tag_no' => 91827319827,
        //         'reader_no' => 1
        //     ];

        //     broadcast(new RfidMiddlewareEvent($message));

        //     echo "Success dispatch RFID Middleware Event";
        // } catch (\Throwable $th) {
        //     dd($th);
        // }
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'ClientType' => 'required',
            'TagNo' => 'required',
            'ReaderNo' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }else {
            $rfid_data = new RfidData;
            $rfid_data->client_type = $request['ClientType'];
            $rfid_data->tag_no = $request['TagNo'];
            $rfid_data->reader_no = $request['ReaderNo'];
            $rfid_data->save();

            broadcast(new RfidMiddlewareEvent($rfid_data->toArray()));

            return response()->json([
                'status' => true,
                'message' => 'Succcess create new data',
                'data' => $rfid_data->toJson()
            ], 200);
        }
    }
}
