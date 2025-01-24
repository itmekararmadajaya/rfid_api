<?php

namespace App\Http\Controllers;

use App\Events\RfidMiddlewareEvent;
use App\Models\RfidData;
use Illuminate\Http\Request;

class DisplayAbsenceController extends Controller
{
    public function index(){
        $rfid_datas = RfidData::all();

        return view('display-absence.index', [
            'rfid_datas' => $rfid_datas
        ]);
    }
}
