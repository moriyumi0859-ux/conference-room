<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'conference_room_id',
        'reservation_date',
        'start_time',
        'end_time',
        'status',
    ];

    protected $casts = [
        'reservation_date' => 'date',
    ];

    // どの会議室か
    public function conferenceRoom()
    {
        return $this->belongsTo(ConferenceRoom::class);
    }

    // どの利用者か
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}