<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class WhatsAppController extends Controller
{
    public function send(Request $request)
    {
        $apiKey = env('FONNTE_API_KEY');

        $bookingDate = $request->input('booking_date', Carbon::today()->toDateString());

        $bookings = Booking::with('category')
            ->whereDate('booking_date', $bookingDate)
            ->get();

        // dd($bookings);      

        foreach ($bookings as $booking) {
            $message = "Halo {$booking->name},\n\n"
                     . "Terima kasih telah melakukan booking! Berikut adalah detail pesanan Anda:\n\n"
                     . "- Nama: {$booking->name}\n"
                     . "- Tipe Motor: {$booking->type}\n"
                     . "- Waktu Mulai: {$booking->timestart}\n"
                     . "- Waktu Selesai: {$booking->timeend}\n"
                     . "- Paket: " . ($booking->category->name ?? 'Tidak ada paket') . "\n\n"
                     . "Silakan hubungi kami jika ada pertanyaan.\n"
                     . "Terima kasih!";
        
            $targets[] = [
                'target' => $booking->phone,
                'message' => $message
            ];
        }

        if (empty($targets)) {
            return response()->json(['message' => 'Tidak ada nomor telepon yang ditemukan di tabel bookings.']);
        }

        foreach ($targets as $target) {
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api.fonnte.com/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => [
                    'target' => $target['target'],
                    'message' => $target['message'],
                    'schedule' => 0,
                    'typing' => false,
                    'delay' => '2',
                    'countryCode' => '62'
                ],
                CURLOPT_HTTPHEADER => [
                    "Authorization: $apiKey"
                ]
            ]);
        
            $response = curl_exec($curl);
            curl_close($curl);
        }

        return view('successmessage');
    }
}
