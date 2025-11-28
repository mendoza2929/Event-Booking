<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Booking;
use App\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
   
    public function store(Request $request, $ticket_id)
    {
        $this->validate($request, [
            'quantity' => 'required|integer|min:1'
        ]);

        $ticket = Ticket::with('event')->findOrFail($ticket_id);

        $quantity = $request->quantity;

  
        if ($ticket->quantity < $quantity) {
            return response()->json([
                'error' => 'Not enough tickets available. Only ' . $ticket->quantity . ' left.'
            ], 400);
        }

        DB::beginTransaction();
        try {
            $booking = Booking::create([
                'user_id'   => Auth::id(),
                'ticket_id' => $ticket_id,
                'quantity'  => $quantity,
                'status'    => 'pending'
            ]);

       
            $ticket->decrement('quantity', $quantity);

            DB::commit();

          
            $booking->total_price = $quantity * $ticket->price;

            return response()->json([
                'message' => 'Booking created successfully',
                'booking' => $booking->load('ticket.event')
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => 'Booking failed: ' . $e->getMessage()
            ], 500);
        }
    }

 
    public function index()
    {
        $bookings = Booking::where('user_id', Auth::id())
                          ->with(['ticket.event', 'payment'])
                          ->orderBy('created_at', 'desc')
                          ->paginate(10);

        return response()->json($bookings);
    }

   
    public function cancel($id)
    {
        $booking = Booking::where('id', $id)
                        ->where('status', 'pending')
                        ->first();

        if (!$booking) {
            return response()->json([
                'error' => 'Booking not found, already cancelled, or not yours'
            ], 404);
        }

        DB::beginTransaction();
        try {
            $booking->ticket->increment('quantity', $booking->quantity);

            $booking->update(['status' => 'cancelled']);

            DB::commit();

            return response()->json([
                'message' => 'Booking cancelled successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Cancellation failed'], 500);
        }
    }
}