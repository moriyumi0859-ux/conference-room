<?php

namespace App\Http\Controllers;

use App\Models\ConferenceRoom;
use Illuminate\Http\Request;

class ConferenceRoomController extends Controller
{
    public function index()
    {
        $rooms = ConferenceRoom::orderBy('name')->get();
        return view('conference-rooms.index', compact('rooms'));
    }
}