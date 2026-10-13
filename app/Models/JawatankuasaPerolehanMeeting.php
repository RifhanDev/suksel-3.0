<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JawatankuasaPerolehanMeeting extends Model
{
    protected $table = 'jawatankuasa_perolehan_meetings';

    protected $fillable = [
        'tender_id',
        'bil_mesyuarat',
        'tarikh_mesyuarat',
        'masa',
        'tajuk_agenda',
        'tempat',
        'no_kod_kertas',
        'status',
        'catatan',
        'submitted_at',
    ];

    protected $casts = [
        'tarikh_mesyuarat' => 'date',
        'submitted_at' => 'datetime',
    ];
}
