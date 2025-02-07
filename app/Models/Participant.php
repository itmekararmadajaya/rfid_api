<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Participant extends Model
{
    protected $fillable = [
        'tag_no',
        'name',
        'mandarin_name',
        'position',
        'city',
        'table_no'
    ];

    public function attendances(): HasMany {
        return $this->hasMany(Attendance::class, 'participant_id');
    }
}
