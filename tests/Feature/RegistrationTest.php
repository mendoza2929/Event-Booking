<?php

class RegistrationTest extends TestCase
{
    public function test_customer_can_register()
    {
        $email = 'test' . rand(1,999999) . '@example.com';

        $response = $this->call('POST', '/api/register', [
            'name'                  => 'John Doe',
            'email'                 => $email,
            'password'              => '123456',
            'password_confirmation' => '123456',
            'role'                  => 'customer'
        ]);

        $this->assertResponseStatus(201);

        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('message', $content);
        $this->assertEquals('Registered successfully', $content['message']);

        // Check database — Laravel 5.0 way
        $user = DB::table('users')->where('email', $email)->first();
        $this->assertNotNull($user);
        $this->assertEquals('customer', $user->role);
    }
}