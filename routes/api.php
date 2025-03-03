<?php

use App\Events\RfidMiddlewareEvent;
use App\Http\Controllers\Api\RfidMiddlewareController;
use App\Models\RfidData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\TryCatch;

Route::post("/absence", [RfidMiddlewareController::class, 'absence']);
Route::post("/acs/{reader_no}", [RfidMiddlewareController::class, 'acs']);
Route::get("/get-latest-attendance/{count}/{reader_no}", [RfidMiddlewareController::class, 'getLatestAttendance']);
Route::get("/get-attendance-today/{reader_no}", [RfidMiddlewareController::class, "getCountAttendanceToday"]);
Route::get("/check-attendance", [RfidMiddlewareController::class, "checkAttendance"]);