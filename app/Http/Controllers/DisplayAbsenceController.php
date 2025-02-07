<?php

namespace App\Http\Controllers;

use App\Events\RfidMiddlewareEvent;
use App\Models\RfidData;
use Illuminate\Http\Request;

class DisplayAbsenceController extends Controller
{
    public function index(){
        return view('display-absence.index');
    }
}
