<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    protected $baseUrl = 'http://localhost';

    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';
        $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
        return $app;
    }


    protected function createUser($overrides = [])
    {
        $defaults = [
            'name'       => 'Test User',
            'email'      => 'test' . rand(1,999999) . '@example.com',
            'password'   => bcrypt('123456'),
            'role'       => 'customer',
            'api_token'  => str_random(60),
        ];

        return App\UserAccount::create(array_merge($defaults, $overrides));
    }

    protected function createOrganizer($overrides = [])
    {
        return $this->createUser(array_merge(['role' => 'organizer'], $overrides));
    }

    protected function createEvent($overrides = [])
    {
        return App\Event::create(array_merge([
            'title'       => 'Test Event',
            'description' => 'Description',
            'date'        => '2025-12-31',
            'location'    => 'Kuala Lumpur',
            'organizer_id'=> 1,
        ], $overrides));
    }

    protected function createTicket($event, $overrides = [])
    {
        return App\Ticket::create(array_merge([
            'event_id' => $event->id,
            'type'     => 'VIP',
            'price'    => 499.00,
            'quantity' => 100,
            'sold'     => 0,
        ], $overrides));
    }

    protected function seeInDatabase($table, array $data)
    {
        foreach ($data as $key => $value) {
            $this->assertGreaterThan(0, DB::table($table)->where($key, $value)->count());
        }
    }

    public function setUp()
    {
        parent::setUp();

        Artisan::call('migrate');

    
        DB::beginTransaction();
    }

    public function tearDown()
    {
        
        DB::rollBack();

        parent::tearDown();
    }

   protected function authenticateUser($user)
    {
        Auth::onceUsingId($user->id);
    }

    
}