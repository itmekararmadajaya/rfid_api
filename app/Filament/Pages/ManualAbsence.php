<?php

namespace App\Filament\Pages;

use App\Events\RfidMiddlewareEvent;
use App\Models\Attendance;
use App\Models\Participant;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Log;

class ManualAbsence extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.manual-absence';

    protected static bool $shouldRegisterNavigation = false;

    public $participant;

    public function mount()
    {
        $id = request('id');

        $this->participant = Participant::where('id', $id)->first();
    }

    public function absence()
    {
        $delay = 0;

        $check_attendance = Attendance::where('participant_id', $this->participant->id)->where('check_in', '>=', Carbon::now()->subMinutes($delay))->exists();
        
        if(isset($check_attendance)){
            $check_dupliacte_attendance_today = Attendance::where('participant_id', $this->participant->id)->wherebetween('check_in', [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()])->exists();

            /**
             * Store data absensi
             */
            $absence = new Attendance();
            $absence->participant_id = $this->participant->id;
            $absence->check_in = Carbon::now();
            $absence->reader_no = '';
            $absence->client_type = '';
            $absence->is_new = $check_dupliacte_attendance_today ? false : true;
            $absence->save();

            $send_data = array_merge($this->participant->toArray(), [
                'source' => 'manual',
                'is_new' => $absence->is_new,
            ]);

            try {
                /**
                 * Mengirim broadcast ke websocket
                 */
                broadcast(new RfidMiddlewareEvent($send_data));

                Notification::make()
                ->title('Success manual absence')
                ->success()
                ->send();
            } catch (\Throwable $th) {
                Log::info($th->getMessage());

                Notification::make()
                ->title('Failed manual absence')
                ->danger()
                ->send();
            }
        }
    }
}
