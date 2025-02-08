<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $bookings = DB::table('bookings')
            ->join('categories', 'bookings.category_id', '=', 'categories.id')
            ->select(
                'bookings.id',
                'bookings.name',
                'bookings.phone',
                'bookings.type',
                'categories.name as category_name',
                'bookings.timestart',
                'bookings.timeend',
                'categories.hour',
                'bookings.created_at as date'
            )
            ->get();
    
        return view('dashboard', ['bookings' => $bookings]);
    }

    public function calculate() {
        // Ambil semua bookings dengan join ke categories untuk mendapatkan durasi layanan (hour)
        $bookings = DB::table('bookings')
            ->join('categories', 'bookings.category_id', '=', 'categories.id')
            ->select('bookings.id', 'bookings.name', 'bookings.phone', 'bookings.type', 'categories.name as category', 'categories.hour')
            ->orderBy('categories.hour', 'asc') // Sorting berdasarkan durasi layanan (Greedy SJF)
            ->get();
    
        $startTime = Carbon::now(); // Waktu awal pengerjaan (misal: saat ini)
        $currentTime = clone $startTime;
        
        foreach ($bookings as $booking) {
            $endTime = $currentTime->copy()->addHours($booking->hour); // Hitung waktu selesai
    
            // Update tabel bookings dengan waktu mulai dan selesai
            DB::table('bookings')
                ->where('id', $booking->id)
                ->update([
                    'start_time' => $currentTime->toDateTimeString(),
                    'end_time' => $endTime->toDateTimeString(),
                    'updated_at' => now(),
                ]);
    
            $currentTime = clone $endTime; // Waktu mulai booking berikutnya
        }
    
        return view('dashboard', ['bookings' => $bookings]);
    }

    public function create()
    {

    }

    
}
