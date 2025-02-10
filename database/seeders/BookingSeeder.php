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
            'name' => 'Favian',
            'phone' => '082117778311',
            'type' => 'Scooter',
            'category_id' => 1,
            'timestart' => null,
            'timeend' => null,
            'booking_date' => '2025-02-10',
        ]);

        Booking::create([
            'name' => 'Alghifari',
            'phone' => '082121256766',
            'type' => 'Sport',
            'category_id' => 2,
            'timestart' => null,
            'timeend' => null,
            'booking_date' => '2025-02-10',
        ]);

        Booking::create([
            'name' => 'Fawwaz',
            'phone' => '081312644241',
            'type' => 'Cruiser',
            'category_id' => 3,
            'timestart' => null,
            'timeend' => null,
            'booking_date' => '2025-02-10',
        ]);

        Booking::create([
            'name' => 'Nafhan',
            'phone' => '081384260738',
            'type' => 'Touring',
            'category_id' => 1,
            'timestart' => null,
            'timeend' => null,
            'booking_date' => '2025-02-10',
        ]);

        Booking::create([
            'name' => 'Rifqy',
            'phone' => '081222074013',
            'type' => 'Dirt Bike',
            'category_id' => 2,
            'timestart' => null,
            'timeend' => null,
            'booking_date' => '2025-02-10',
        ]);

        Booking::create([
            'name' => 'Rehan',
            'phone' => '087827118371',
            'type' => 'Beat',
            'category_id' => 1,
            'timestart' => null,
            'timeend' => null,
            'booking_date' => '2025-02-11',
        ]);

        Booking::create([
            'name' => 'Bourbon',
            'phone' => '081219031704',
            'type' => 'Vario',
            'category_id' => 3,
            'timestart' => null,
            'timeend' => null,
            'booking_date' => '2025-02-12',
        ]);
    }
}
