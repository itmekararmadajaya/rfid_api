<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RfidData extends Model
{
    use HasFactory;

    protected $table = 'rfid_data';

    protected $fillable = [
        'client_type',
        'tag_no',
        'reader_no',
    ];
}
