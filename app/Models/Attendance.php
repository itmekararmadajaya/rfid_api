<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'participant_id',
        'check_in',
        'reader_no',
        'client_type'
    ];

    public function participant(): BelongsTo {
        return $this->belongsTo(Participant::class, 'participant_id');
    }
}
