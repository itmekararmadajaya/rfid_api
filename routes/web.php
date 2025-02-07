<?php

use App\Http\Controllers\DisplayAbsenceController;
use App\Models\RfidData;
use Illuminate\Support\Facades\Route;

Route::get('/', [DisplayAbsenceController::class, 'index']);