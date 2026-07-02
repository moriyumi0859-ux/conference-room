<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with(['user', 'conferenceRoom'])
            ->where('status', '予約中')
            ->orderBy('reservation_date')
            ->orderBy('start_time')
            ->paginate(20);

        return view('admin.reservations.index', compact('reservations'));
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->update(['status' => 'キャンセル']);
        return redirect()->route('admin.reservations.index')
            ->with('success', '予約をキャンセルしました。');
    }
}