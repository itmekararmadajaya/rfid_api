<?php

use App\Http\Controllers\Api\RfidMiddlewareController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get("/list", [RfidMiddlewareController::class, 'list']);
Route::post("/store", [RfidMiddlewareController::class, 'store']);