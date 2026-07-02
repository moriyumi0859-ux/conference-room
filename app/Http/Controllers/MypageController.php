<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class MypageController extends Controller
{
    public function index()
    {
        $reservations = Auth::user()->reservations()
            ->where('status', '予約中')
            ->with('conferenceRoom')
            ->orderBy('reservation_date')
            ->orderBy('start_time')
            ->get();

        return view('mypage', compact('reservations'));
    }
}