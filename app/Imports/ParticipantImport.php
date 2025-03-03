<?php

namespace App\Imports;

use App\Models\Participant;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ParticipantImport implements ToModel, WithHeadingRow, WithValidation
{
    public $successfulRows = [];
    public $failedRows = [];
    public $rowNumber = 1;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $participant = Participant::updateOrCreate([
            'tag_no' => $row['tag_no']
        ], [
            'name' => $row['name'],
            'mandarin_name' => $row['mandarin_name'],
            'position' => $row['position'],
            'city' => $row['city'],
            'table_no' => $row['table_no']
        ]);
        
        return $participant;
    }

    public function rules(): array
    {
        return [
            'tag_no' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $participant = Participant::where('tag_no', $value)->first();
                    if ($participant) {
                        $fail("Tag No $value sudah digunakan.");
                    }
                },
            ],
        ];
    }
}
