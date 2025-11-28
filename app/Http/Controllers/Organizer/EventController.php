<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'date'        => 'required|date',
            'location'    => 'required|string',
        ]);

        $event = Event::create([
            'title'         => $request->title,
            'description'   => $request->description,
            'date'          => $request->date,
            'location'      => $request->location,
            'created_by'  => Auth::id(),   
        ]);

        return response()->json([
            'message' => 'Event created successfully',
            'event'   => $event
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $event = Event::where('id', $id)->first();

        if (!$event) {
            return response()->json(['error' => 'Event not found or not owned by you'], 404);
        }

        $event->update($request->all());

        return response()->json([
            'message' => 'Event updated',
            'event'   => $event
        ]);
    }

    public function destroy($id)
    {
        $event = Event::where('id', $id)->first();

        if (!$event) {
            return response()->json(['error' => 'Event not found or not owned by you'], 404);
        }

        $event->delete();

        return response()->json(['message' => 'Event deleted successfully']);
    }
}