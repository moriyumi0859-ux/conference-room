<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConferenceRoom;
use Illuminate\Http\Request;

class ConferenceRoomController extends Controller
{
    public function index()
    {
        $rooms = ConferenceRoom::orderBy('name')->get();
        return view('admin.conference-rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('admin.conference-rooms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255|unique:conference_rooms,name',
            'capacity'  => 'required|integer|min:1',
            'equipment' => 'nullable|string|max:255',
        ]);

        ConferenceRoom::create([
            'name'      => $request->name,
            'capacity'  => $request->capacity,
            'equipment' => $request->equipment,
        ]);

        return redirect()->route('admin.conference-rooms.index')
            ->with('success', '会議室を登録しました。');
    }

    public function edit(ConferenceRoom $conferenceRoom)
    {
        return view('admin.conference-rooms.edit', compact('conferenceRoom'));
    }

    public function update(Request $request, ConferenceRoom $conferenceRoom)
    {
        $request->validate([
            'name'      => 'required|string|max:255|unique:conference_rooms,name,' . $conferenceRoom->id,
            'capacity'  => 'required|integer|min:1',
            'equipment' => 'nullable|string|max:255',
        ]);

        $conferenceRoom->update([
            'name'      => $request->name,
            'capacity'  => $request->capacity,
            'equipment' => $request->equipment,
        ]);

        return redirect()->route('admin.conference-rooms.index')
            ->with('success', '会議室を更新しました。');
    }

    public function destroy(ConferenceRoom $conferenceRoom)
    {
        $conferenceRoom->delete();
        return redirect()->route('admin.conference-rooms.index')
            ->with('success', '会議室を削除しました。');
    }
}