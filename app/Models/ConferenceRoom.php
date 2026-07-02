<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConferenceRoom extends Model
{
    protected $fillable = [
        'name',
        'capacity',
        'equipment',
    ];

    protected $casts = [
        'capacity' => 'integer',
    ];

    // 予約との関係
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}