<?php

namespace App\Services;

use App\Payment;
use App\Booking;
use App\Mail\BookingConfirmed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PaymentService
{
    public function processPayment(Booking $booking)
    {
        DB::transaction(function () use ($booking) {
            $amount = $booking->quantity * $booking->ticket->price;

       
            Payment::updateOrCreate(
                ['booking_id' => $booking->id],
                [
                    'amount' => $amount,       
                    'status' => 'success'
                ]
            );

        });
        // \Queue::push(new BookingConfirmed($booking));
    }
}