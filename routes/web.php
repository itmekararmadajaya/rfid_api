<?php

use App\Http\Controllers\DisplayAbsenceController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DisplayAbsenceController::class, 'index']);
