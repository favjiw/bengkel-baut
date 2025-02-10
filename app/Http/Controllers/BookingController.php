<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Category;
use Illuminate\Container\Attributes\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return view('FormPage', ['categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'type' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'timestart' => 'nullable|date_format:H:i',
            'timeend' => 'nullable|date_format:H:i',
            'booking_date' => 'required|date',
        ]);
    
        // Hitung total durasi dari menit di tanggal itu
        $totalMinutesBooked = DB::table('bookings')
            ->join('categories', 'bookings.category_id', '=', 'categories.id')
            ->whereDate('bookings.booking_date', $request->booking_date)
            ->sum('categories.minute'); 
    
        // Ambil durasi kategori yang dipilih
        $categoryMinutes = DB::table('categories')
            ->where('id', $request->category_id)
            ->value('minute'); // Ambil durasi kategori yang dipilih
    
        // Periksa apakah total durasi setelah booking ini melebihi 600 menit
        if (($totalMinutesBooked + $categoryMinutes) > 600) {
            return redirect()->back()->withErrors(['booking_date' => 'Total booking di tanggal ini sudah mencapai batas 10 jam (600 menit).'])->withInput();
        }
    
        // Simpan booking jika masih dalam batas waktu
        Booking::create($validatedData);
    
        return redirect()->route('booking')->with('success', 'Booking created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
