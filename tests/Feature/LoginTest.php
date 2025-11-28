<?php

class LoginTest extends TestCase
{
    public function test_customer_can_login()
    {
       
        $user = $this->createUser([
            'password' => bcrypt('123456'),
            'role'     => 'customer'
        ]);

        $response = $this->call('POST', '/api/login', [
            'email'    => $user->email,
            'password' => '123456'
        ]);

        $this->assertResponseOk();

        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('api_token', $data);
        $this->assertNotEmpty($data['api_token']);
    }
}