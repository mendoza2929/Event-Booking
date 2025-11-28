<?php

use App\Services\PaymentService; 

class PaymentServiceTest extends TestCase
{
    public function test_payment_service_creates_payment_and_updates_booking()
    {
        
        $organizer = App\UserAccount::create([
            'name'      => 'Org',
            'email'     => 'org_' . uniqid() . '@test.com',
            'password'  => bcrypt('123456'),
            'role'      => 'organizer',
            'api_token' => str_random(60)
        ]);

        $event = App\Event::create([
            'title'       => 'Jazz Night',
            'description' => 'Smooth',
            'date'        => '2025-12-25',
            'location'    => 'Jazz Club',
            'created_by'  => $organizer->id
        ]);

        $ticket = App\Ticket::create([
            'event_id' => $event->id,
            'type'     => 'Standard',
            'price'    => 250.00,
            'quantity' => 100,
            'sold'     => 0
        ]);

        $customer = App\UserAccount::create([
            'name'      => 'Customer',
            'email'     => 'cust_' . uniqid() . '@test.com',
            'password'  => bcrypt('123456'),
            'role'      => 'customer',
            'api_token' => str_random(60)
        ]);

        $booking = App\Booking::create([
            'user_id'   => $customer->id,
            'ticket_id' => $ticket->id,
            'quantity'  => 4,
            'status'    => 'pending'
        ]);

        $service = new PaymentService();
        $service->processPayment($booking);

        $this->assertEquals('paid', $booking->fresh()->status);

        $payment = DB::table('payments')
                     ->where('booking_id', $booking->id)
                     ->first();

        $this->assertNotNull($payment);
        $this->assertEquals('paid', $payment->status);
        $this->assertEquals(1000.00, $payment->amount);
    }
}