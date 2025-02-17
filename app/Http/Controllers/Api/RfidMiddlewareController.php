<?php

namespace App\Http\Controllers\Api;

use App\Events\RfidMiddlewareEvent;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Participant;
use App\Models\RfidData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RfidMiddlewareController extends Controller
{
    public function absence(Request $request){
        Log::info("HIT API $request->TagNo");
        
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
            /**
             * Mencocokan tag_no dengan data participant
             */
            $get_participant = Participant::where('tag_no', $request['TagNo'])->first();

            if(isset($get_participant)){
                if(isset($get_participant)){
                    /**
                     * Store data absensi
                     */
                    $absence = new Attendance;
                    $absence->participant_id = $get_participant->id;
                    $absence->check_in = Carbon::now();
                    $absence->reader_no = $request['ReaderNo'];
                    $absence->client_type = $request['ClientType'];
                    $absence->save();
                }
                Log::info("Success add new data");

                try {
                    /**
                     * Mengirim broadcast ke websocket
                     */
                    broadcast(new RfidMiddlewareEvent($get_participant->toArray()));
                    Log::info("Success broadcast data");
                } catch (\Throwable $th) {
                    Log::info("Failed broadcast data");
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Succcess create new data',
                    'data' => $absence->toJson()
                ], 200);
                
                /**
                 * Store attendance per 15minutes
                 */
                // $check_attendance = Attendance::where('participant_id', $get_participant->id)->where('check_in', '>=', Carbon::now()->subMinutes(1))->exists();

                // if(!$check_attendance){
                //     if(isset($get_participant)){
                //         $absence = new Attendance;
                //         $absence->participant_id = $get_participant->id;
                //         $absence->check_in = Carbon::now();
                //         $absence->reader_no = $request['ReaderNo'];
                //         $absence->client_type = $request['ClientType'];
                //         $absence->save();
                //     }
                //     Log::info("Success add new data");
    
                //     try {
                //         broadcast(new RfidMiddlewareEvent($absence->reader_no, $get_participant->toArray()));
                //         Log::info("Success broadcast data");
                //     } catch (\Throwable $th) {
                //         Log::info("Failed broadcast data");
                //     }
    
                //     return response()->json([
                //         'status' => true,
                //         'message' => 'Succcess create new data',
                //         'data' => $absence->toJson()
                //     ], 200);
                // }else{
                //     return response()->json([
                //         'status' => false,
                //         'message' => 'User has been absent',
                //     ], 200);
                // }
            }
        }
    }

    public function acs(Request $request){
        $delay = 0;
        Log::info("Success HIT from reader ACS");
        Log::info("Data from reader", $request->toArray());
        foreach($request->toArray() as $req){
            $tag_no = $req['data']['idHex'];
            Log::info("ID HEX = $tag_no");

            $get_participant = Participant::where('tag_no', $tag_no)->first();
            if(isset($get_participant)){
                $check_attendance = Attendance::where('participant_id', $get_participant->id)->where('check_in', '>=', Carbon::now()->subMinutes($delay))->exists();
                
                if(!$check_attendance){
                    /**
                     * Store data absensi
                     */
                    $absence = new Attendance;
                    $absence->participant_id = $get_participant->id;
                    $absence->check_in = Carbon::now();
                    $absence->reader_no = '1';
                    $absence->client_type = 'asd';
                    $absence->save();

                    Log::info("Success add new data");

                    try {
                        /**
                         * Mengirim broadcast ke websocket
                         */
                        broadcast(new RfidMiddlewareEvent($get_participant->toArray()));
                        Log::info("Success broadcast data");
                    } catch (\Throwable $th) {
                        Log::info($th->getMessage());
                        Log::info("Failed broadcast data");
                    }
                }else{
                    Log::info("User has already attendance");
                }
            }
        }
    }

    public function getLatestAttendance($count){
        $getAttendance = Attendance::selectRaw("MAX(id) as id, participant_id, MAX(check_in) as check_in")
                ->groupBy('participant_id')
                ->whereDate('check_in', Carbon::today())
                ->orderByDesc('check_in')
                ->skip(1)
                ->take($count)
                ->get()
                ->pluck('participant_id');
        $participants = Participant::select("id", "tag_no", "name", "mandarin_name", "position", "city", "table_no")->whereIn('id', $getAttendance)->get();
        echo json_encode($participants);
    }

    public function getCountAttendanceToday(){
        $attendanceToday = Attendance::selectRaw("MAX(id) as id, participant_id, MAX(check_in) as check_in")
                ->groupBy('participant_id')
                ->whereDate('check_in', Carbon::today())
                ->get();

        echo count($attendanceToday);
    }
}
