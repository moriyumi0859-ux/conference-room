<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConferenceRoom;
use App\Models\Reservation;

class DashboardController extends Controller
{
    public function index()
    {
        // 会議室の総数
        $roomCount = ConferenceRoom::count();

        // 当日の予約件数
        $todayReservationCount = Reservation::where('reservation_date', today())
            ->where('status', '予約中')
            ->count();

        // 当日まだ予約のない会議室数
        $availableRoomCount = ConferenceRoom::whereDoesntHave('reservations', function ($query) {
            $query->where('reservation_date', today())
                  ->where('status', '予約中');
        })->count();

        return view('admin.dashboard', compact(
            'roomCount',
            'todayReservationCount',
            'availableRoomCount'
        ));
    }
}