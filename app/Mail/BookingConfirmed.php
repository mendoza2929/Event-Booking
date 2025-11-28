<?php

namespace App\Mail;

use App\Booking;
use Illuminate\Queue\SerializesModels;

class BookingConfirmed
{
    use SerializesModels;

    public $booking;

    public function handle($job, $data)
    {
        $this->booking = Booking::with('user', 'ticket.event')->findOrFail($data['booking_id']);

        $user  = $this->booking->user;
        $event = $this->booking->ticket->event;

        \Mail::send('emails.booking_confirmed', [
            'name'        => $user->name,
            'event_title' => $event->title,
            'quantity'    => $this->booking->quantity,
            'total'       => '$' . number_format($this->booking->quantity * $this->booking->ticket->price, 2),
            'date'        => $event->date,
        ], function ($message) use ($user) {
            $message->to($user->email)->subject('Your Booking is Confirmed!');
        });

        $job->delete(); 
    }
}