<?php

use App\Http\Controllers\DisplayAbsenceController;
use App\Models\RfidData;
use Illuminate\Support\Facades\Route;

Route::get('/', [DisplayAbsenceController::class, 'index']);
Route::get('/count_db', function(){
    $count = RfidData::count();
    dd($count);
});
