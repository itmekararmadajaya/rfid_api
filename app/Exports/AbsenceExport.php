<?php

namespace App\Exports;

use App\Models\Attendance;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AbsenceExport implements FromCollection, WithHeadings
{
    public $date_start, $date_end;

    public function __construct($date_start, $date_end){
        $this->date_start = $date_start;
        $this->date_end = $date_end;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection(): Collection
    {
        return Attendance::select('participants.tag_no', 'participants.name', 'participants.mandarin_name', 'participants.position', 'participants.city', 'attendances.check_in', 'attendances.is_new', 'attendances.reader_no')
        ->leftJoin('participants', 'participants.id', '=', 'attendances.participant_id')
        ->whereDate('check_in', '>=', $this->date_start)
        ->whereDate('check_in', '<=', $this->date_end)
        ->get();
    }

    public function headings(): array
    {
        return [
            'Tag RFID',
            'Name',
            'Mandarin Name',
            'Position',
            'City',
            'Check In',
            'Is New',
            'Reader No'
        ];
    }
}
