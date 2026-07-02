<?php

namespace App\Http\Controllers;

use App\Models\ConferenceRoom;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'conference_room_id' => 'required|exists:conference_rooms,id',
            'reservation_date'   => 'required|date|after_or_equal:today',
            'start_time'         => 'required',
            'end_time'           => 'required|after:start_time',
        ]);

        // 二重予約チェック
        $overlap = Reservation::where('conference_room_id', $request->conference_room_id)
            ->where('reservation_date', $request->reservation_date)
            ->where('status', '予約中')
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                      });
            })
            ->exists();

        if ($overlap) {
            return back()->with('error', 'その時間帯はすでに予約が入っています。');
        }

        Reservation::create([
            'user_id'            => Auth::id(),
            'conference_room_id' => $request->conference_room_id,
            'reservation_date'   => $request->reservation_date,
            'start_time'         => $request->start_time,
            'end_time'           => $request->end_time,
            'status'             => '予約中',
        ]);

        return redirect()->route('mypage')->with('success', '予約が完了しました。');
    }

    public function destroy(Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id()) {
            abort(403);
        }

        $reservation->update(['status' => 'キャンセル']);
        return redirect()->route('mypage')->with('success', '予約をキャンセルしました。');
    }
}