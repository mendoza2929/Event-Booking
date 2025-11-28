<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Ticket;
use App\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
   
    public function store(Request $request, $event_id)
    {
        
        $this->validate($request, [
            'type'     => 'required|string|max:50',
            'price'    => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1'
        ]);

        $event = Event::where('id', $event_id)
                      ->first();

        if (!$event) {
            return response()->json([
                'error' => 'Event not found or you are not the organizer'
            ], 404);
        }


        $ticket = Ticket::create([
            'event_id'  => $event_id,
            'type'      => $request->type,
            'price'     => $request->price,
            'quantity'  => $request->quantity
        ]);

        return response()->json([
            'message' => 'Ticket created successfully',
            'ticket'  => $ticket
        ], 201);
    }

  
    public function update(Request $request, $id)
    {
        $ticket = Ticket::where('id', $id)
                        ->first();

        if (!$ticket) {
            return response()->json(['error' => 'Ticket not found or not yours'], 404);
        }

        $updateData = $request->only(['price', 'quantity']);
        $ticket->update($updateData);
        return response()->json([
            'message' => 'Ticket updated',
            'ticket'  => $ticket->fresh()
        ]);
    }

  
    public function destroy($id)
    {
        $ticket = Ticket::where('id', $id)
                        ->first();

        if (!$ticket) {
            return response()->json(['error' => 'Ticket not found or not yours'], 404);
        }

        $ticket->delete();

        return response()->json(['message' => 'Ticket deleted successfully']);
    }
}