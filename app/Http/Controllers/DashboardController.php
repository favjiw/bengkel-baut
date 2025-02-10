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
                'categories.minute',
                'bookings.booking_date'
            )
            ->whereDate('bookings.booking_date', Carbon::today())
            ->orderByRaw("CASE WHEN bookings.timestart IS NULL THEN bookings.created_at ELSE bookings.timestart END ASC")
            ->get();

        $bookings_unsorted = DB::table('bookings')
            ->join('categories', 'bookings.category_id', '=', 'categories.id')
            ->select(
                'bookings.id',
                'bookings.name',
                'bookings.phone',
                'bookings.type',
                'categories.name as category_name',
                'bookings.timestart',
                'bookings.timeend',
                'categories.minute',
                'bookings.booking_date'
            )
            ->whereDate('bookings.booking_date', Carbon::today())
            ->orderBy('bookings.created_at', 'ASC')
            ->get();
        $bookingDate = Carbon::today()->toDateString();
        return view('dashboard', ['bookings' => $bookings, 'bookingDate' => $bookingDate, 'bookings_unsorted' => $bookings_unsorted]);
    }

    public function search(Request $request){
        // Ambil tanggal booking dari input form, default ke hari ini jika tidak ada input
        $bookingDate = $request->input('booking_date', Carbon::today()->toDateString());

        // Query booking berdasarkan booking_date
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
                'categories.minute',
                'bookings.booking_date'
            )
            ->whereDate('bookings.booking_date', $bookingDate) // Filter berdasarkan booking_date
            ->orderByRaw("CASE WHEN bookings.timestart IS NULL THEN bookings.created_at ELSE bookings.timestart END ASC")
            ->get();
        
        $bookings_unsorted = DB::table('bookings')
        ->join('categories', 'bookings.category_id', '=', 'categories.id')
        ->select(
            'bookings.id',
            'bookings.name',
            'bookings.phone',
            'bookings.type',
            'categories.name as category_name',
            'bookings.timestart',
            'bookings.timeend',
            'categories.minute',
            'bookings.booking_date'
        )
        ->whereDate('bookings.booking_date', $bookingDate)
        ->orderBy('bookings.created_at', 'ASC')
        ->get();

        return view('dashboard', compact('bookings', 'bookingDate', 'bookings_unsorted'));
    }

    public function calculate() {
        // Favian - Query data booking hari ini
        $bookings = DB::table('bookings')
            ->join('categories', 'bookings.category_id', '=', 'categories.id')
            ->select(
                'bookings.id',
                'bookings.name',
                'bookings.phone',
                'bookings.type',
                'categories.name as category_name',
                'categories.minute', // Durasi pengerjaan dalam menit
                'bookings.created_at as date'
            )
            ->whereDate('bookings.created_at', Carbon::today())
            ->get()
            ->toArray(); // Ubah collection menjadi array untuk manipulasi lebih mudah
        
        // Periksa kalau tidak ada booking hari ini
        if (empty($bookings)) {
            return response()->json(['message' => 'Tidak ada booking hari ini'], 404);
        }
    
        // Proses Pengurutan Booking 
        $N = count($bookings);
        
        for ($i = 0; $i < $N - 1; $i++) {
            $minIndex = $i;
            
            for ($j = $i + 1; $j < $N; $j++) {
                if ($bookings[$j]->minute < $bookings[$minIndex]->minute) {
                    $minIndex = $j;
                }
            }
    
            // Tukar posisi 
            if ($minIndex !== $i) {
                $temp = $bookings[$i];
                $bookings[$i] = $bookings[$minIndex];
                $bookings[$minIndex] = $temp;
            }
        }
    
        // Proses pelayanan setelah pengurutan
        $processed = []; // Untuk menyimpan urutan booking
        $currentTime = Carbon::createFromTime(7, 0, 0); // Waktu mulai kerja: 07:00
    
        foreach ($bookings as $booking) {
            $endTime = (clone $currentTime)->addMinutes($booking->minute);
    
            // Simpan urutan pelayanan
            $processed[] = [
                'id' => $booking->id,
                'name' => $booking->name,
                'timestart' => $currentTime->format('H:i'),
                'timeend' => $endTime->format('H:i')
            ];
    
            // Update database dengan waktu mulai dan selesai
            DB::table('bookings')
                ->where('id', $booking->id)
                ->update([
                    'timestart' => $currentTime->format('H:i:s'),
                    'timeend' => $endTime->format('H:i:s')
                ]);
    
            // Perbarui waktu mulai untuk booking berikutnya
            $currentTime = $endTime;
        }
    
        return back();
    }
      

    public function create()
    {

    }

    
}
