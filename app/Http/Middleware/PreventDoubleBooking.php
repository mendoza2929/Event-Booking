<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Booking;

class PreventDoubleBooking
{
    public function handle($request, Closure $next)
    {
        $ticketId = $request->route('ticket'); // from route: tickets/{ticket}/bookings

        if ($ticketId) {
            $hasBooked = Booking::where('user_id', Auth::id())
                                ->where('ticket_id', $ticketId)
                                ->whereIn('status', ['pending', 'paid'])
                                ->exists();

            if ($hasBooked) {
                return response()->json([
                    'error' => 'You have already booked this ticket. Double booking is not allowed.'
                ], 403);
            }
        }

        return $next($request);
    }
}