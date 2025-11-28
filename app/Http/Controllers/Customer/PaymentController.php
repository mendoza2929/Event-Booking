<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Booking;
use App\Payment;    
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

  
    public function pay($booking_id)
    {
   
        $booking = Booking::where('id', $booking_id)
                          ->where('status', 'pending')
                          ->with('ticket.event')
                          ->first();

        if (!$booking) {
            return response()->json([
                'error' => 'Booking not found, already paid, cancelled, or not yours.'
            ], 404);
        }

        try {
         
            $this->paymentService->processPayment($booking);

            $booking = Booking::with('ticket.event')
                          ->find($booking->id);  

            $payment = Payment::where('booking_id', $booking->id)->first();

            return response()->json([
                'message' => 'Payment successful! Your ticket is confirmed.',
                'payment' => $payment,
                'booking' => $booking
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Payment failed',
                'message' => $e->getMessage()  
            ], 402); 
        }
    }

 
    public function show($id)
    {
        $payment = Payment::where('id', $id)
                          ->with('booking.ticket.event')
                          ->first();

        if (!$payment) {
            return response()->json([
                'error' => 'Payment not found or you do not have access.'
            ], 404);
        }

        return response()->json($payment);
    }
}