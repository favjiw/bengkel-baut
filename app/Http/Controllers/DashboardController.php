<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $bookings = Booking::all();
        return view('dashboard', ['bookings' => $bookings]);
    }

    public function create()
    {
        //
    }

    
}
