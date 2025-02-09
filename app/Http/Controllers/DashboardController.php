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
            ->get();
    
        // Periksa kalau tidak ada booking hari ini
        if ($bookings->isEmpty()) {
            return response()->json(['message' => 'Tidak ada booking hari ini'], 404);
        }

        // Jika ada lanjut ke sini
        // Proses pengurutan booking 
        $processed = []; // Untuk menyimpan urutan booking
        $visited = []; // Menandai booking yang sudah diproses
        $currentTime = Carbon::createFromTime(7, 0, 0); // Waktu mulai kerja: 07:00
    
        while (count($processed) < count($bookings)) {
            $minIndex = -1;
            $minMinute = PHP_INT_MAX;
    
            // Cari booking dengan durasi tersingkat yang belum diproses
            foreach ($bookings as $index => $booking) {
                if (!in_array($booking->id, $visited) && $booking->minute < $minMinute) {
                    $minMinute = $booking->minute;
                    $minIndex = $index;
                }
            }
    
            // Jika tidak ada yang bisa diproses, keluar dari loop
            if ($minIndex == -1) break;
    
            // Ambil booking dengan waktu pelayanan tersingkat
            $selected = $bookings[$minIndex];
    
            // Hitung waktu selesai pengerjaan
            $endTime = (clone $currentTime)->addMinutes($selected->minute);
    
            // Simpan urutan & update database
            $processed[] = [
                'id' => $selected->id,
                'name' => $selected->name,
                'timestart' => $currentTime->format('H:i'),
                'timeend' => $endTime->format('H:i')
            ];
    
            DB::table('bookings')
                ->where('id', $selected->id)
                ->update([
                    'timestart' => $currentTime->format('H:i:s'),
                    'timeend' => $endTime->format('H:i:s')
                ]);
    
            // Tambahkan booking ke daftar yang telah diproses
            $visited[] = $selected->id;
    
            // Perbarui waktu mulai untuk booking berikutnya
            $currentTime = $endTime;
        }
    
        return back()->with('success', 'Booking telah diurutkan berdasarkan durasi pengerjaan.');
    }    

    public function create()
    {

    }

    
}
