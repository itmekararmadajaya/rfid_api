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