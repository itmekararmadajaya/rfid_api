<?php

use App\Events\RfidMiddlewareEvent;
use App\Http\Controllers\Api\RfidMiddlewareController;
use App\Models\RfidData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get("/list", [RfidMiddlewareController::class, 'list']);
Route::post("/store", [RfidMiddlewareController::class, 'store']);

Route::post("/1", function(Request $request){
    Log::info("Hit Request");

    $validator = Validator::make($request->all(), [
        'ClientType' => 'required',
        'TagNo' => 'required',
        'ReaderNo' => 'required'
    ]);

    if($validator->fails()){
        Log::info("Falidation Failed");
    }else {
        $rfid_data = new RfidData();
        $rfid_data->client_type = $request['ClientType'];
        $rfid_data->tag_no = $request['TagNo'];
        $rfid_data->reader_no = $request['ReaderNo'];
        $rfid_data->save();
        
        Log::info("Data Stored");
    }
});