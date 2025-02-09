<?php

namespace Database\Seeders;
use App\Models\Booking;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Booking::create([
            'name' => 'John Doe',
            'phone' => '082117778311',
            'type' => 'Scooter',
            'category_id' => 1,
            'timestart' => null,
            'timeend' => null,
            'booking_date' => '2025-02-10',
        ]);

        Booking::create([
            'name' => 'Jane Smith',
            'phone' => '082121256766',
            'type' => 'Sport',
            'category_id' => 2,
            'timestart' => null,
            'timeend' => null,
            'booking_date' => '2025-02-10',
        ]);

        Booking::create([
            'name' => 'Michael Johnson',
            'phone' => '081312644241',
            'type' => 'Cruiser',
            'category_id' => 3,
            'timestart' => null,
            'timeend' => null,
            'booking_date' => '2025-02-10',
        ]);

        Booking::create([
            'name' => 'Emily Davis',
            'phone' => '081384260738',
            'type' => 'Touring',
            'category_id' => 1,
            'timestart' => null,
            'timeend' => null,
            'booking_date' => '2025-02-10',
        ]);

        Booking::create([
            'name' => 'Daniel Brown',
            'phone' => '081222074013',
            'type' => 'Dirt Bike',
            'category_id' => 2,
            'timestart' => null,
            'timeend' => null,
            'booking_date' => '2025-02-10',
        ]);
    }
}
