<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
    {
        Model::unguard();                 

        $faker = Faker\Factory::create();    

      
        foreach (range(1, 5) as $i) {
            App\UserAccount::create([
                'name'     => $faker->name,
                'email'    => "admin{$i}@example.com",
                'password' => bcrypt('password'),
                'phone'    => $faker->phoneNumber,
                'role'     => 'admin',
            ]);
        }

      
        foreach (range(1, 15) as $i) {
            App\UserAccount::create([
                'name'     => $faker->name,
                'email'    => "organizer{$i}@example.com",
                'password' => bcrypt('password'),
                'phone'    => $faker->phoneNumber,
                'role'     => 'organizer',
            ]);
        }

        foreach (range(1, 100) as $i) {
            App\UserAccount::create([
                'name'     => $faker->name,
                'email'    => "customer{$i}@example.com",
                'password' => bcrypt('password'),
                'phone'    => $faker->optional(0.8)->phoneNumber,
                'role'     => 'customer',
            ]);
        }

  
        $organizerIds = App\UserAccount::whereIn('role', ['admin', 'organizer'])
                                ->lists('id');                  
      

       
        foreach (range(1, 30) as $i) {
            $event = App\Event::create([
                'title'       => $faker->sentence(4),
                'description' => $faker->paragraph(4),
                'date'        => $faker->dateTimeBetween('now', '+1 year'),
                'location'    => $faker->city . ', ' . $faker->country,
                'created_by'  => $faker->randomElement($organizerIds),
            ]);

          
            foreach (range(1, rand(2, 6)) as $j) {
                App\Ticket::create([
                    'type'     => $faker->randomElement(['VIP', 'Standard', 'Early Bird', 'Student', 'General']),
                    'price'    => $faker->randomFloat(2, 20, 500),
                    'quantity' => $faker->numberBetween(50, 1000),
                    'event_id' => $event->id,
                ]);
            }
        }

       
        $customerIds = App\UserAccount::where('role', 'customer')->lists('id');   
        $tickets     = App\Ticket::all();

        foreach (range(1, 200) as $i) {
            $ticket   = $faker->randomElement($tickets->toArray()); 
            $quantity = $faker->numberBetween(1, min(5, $ticket['quantity']));

            $booking = App\Booking::create([
                'user_id'   => $faker->randomElement($customerIds),
                'ticket_id' => $ticket['id'],
                'quantity'  => $quantity,
                'status'    => $faker->randomElement(['pending', 'confirmed', 'cancelled']),
            ]);

            if ($booking->status !== 'cancelled') {
                App\Payment::create([
                    'booking_id' => $booking->id,
                    'amount'     => $ticket['price'] * $quantity,
                    'status'     => $faker->randomElement(['success', 'failed', 'refunded']),
                ]);
            }
        }

        Model::reguard();
    }

}
