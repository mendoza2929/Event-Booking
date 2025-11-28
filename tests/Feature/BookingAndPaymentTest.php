<?php

use App\Services\PaymentService;   

class BookingAndPaymentTest extends TestCase
{
    public function test_customer_can_book_and_pay()
    {
        $customer = App\UserAccount::create([
            'name'       => 'Test Customer',
            'email'      => 'cust' . time() . '@test.com',
            'password'   => bcrypt('123456'),
            'role'       => 'customer',
            'api_token'  => str_random(60)
        ]);

        $organizer = App\UserAccount::create([
            'name'       => 'Organizer',
            'email'      => 'org' . time() . '@test.com',
            'password'   => bcrypt('123456'),
            'role'       => 'organizer',
            'api_token'  => str_random(60)
        ]);

        $event = App\Event::create([
            'title'       => 'Test Event',
            'description' => 'Test',
            'date'        => '2025-12-31',
            'location'    => 'KL',
            'created_by'  => $organizer->id,
            'status'      => 'active'
        ]);

        $ticket = App\Ticket::create([
            'event_id' => $event->id,
            'type'     => 'VIP',
            'price'    => 499.00,
            'quantity' => 100,
            'sold'     => 0
        ]);

        $headers = [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $customer->api_token
        ];

        $response = $this->call('POST', "/api/tickets/{$ticket->id}/bookings", [
            'quantity' => 2
        ], [], [], $headers);

        $this->assertEquals(201, $response->getStatusCode());

        $content   = json_decode($response->getContent(), true);
        $bookingId = $content['booking']['id'];

        
        $payResponse = $this->call('POST', "/api/bookings/{$bookingId}/payment", [], [], [], $headers);
        $this->assertEquals(200, $payResponse->getStatusCode());

        $booking = App\Booking::find($bookingId);
        $this->assertEquals('paid', $booking->status);

        $payment = App\Payment::where('booking_id', $bookingId)->first();
        $this->assertNotNull($payment);
        $this->assertEquals(998.00, $payment->amount);
    }
}