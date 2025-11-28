<?php

use App\Commands\Command;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BookingConfirmedHandler extends Command implements SelfHandling, ShouldBeQueued
{
    use InteractsWithQueue, SerializesModels;

    protected $booking;

    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    public function handle()
    {
        $user = $this->booking->user;
        $event = $this->booking->ticket->event;

        \Mail::send('emails.booking_confirmed', [
            'name'        => $user->name,
            'event_title' => $event->title,
            'quantity'    => $this->booking->quantity,
            'total'       => '$' . number_format($this->booking->quantity * $this->booking->ticket->price, 2),
            'date'        => $event->date,
        ], function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Your Booking is Confirmed!');
        });
    }
}