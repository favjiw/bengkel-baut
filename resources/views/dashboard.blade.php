@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
    <h2>Booking List</h2>
    <form action="{{ url('dashboard/search') }}" method="GET">
        <div class="date-button">
            <input type="date" name="booking_date" id="date" value="{{ request('booking_date') }}">
            <button type="submit">Search</button>
        </div>
    </form>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Phone</th>
                <th>Tipe Motor</th>
                <th>Kategori</th>
                <th>Lama Pengerjaan (Menit)</th>
                <th>Waktu Mulai</th>
                <th>Waktu Selesai</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bookings as $booking)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $booking->name }}</td>
                    <td>{{ $booking->phone }}</td>
                    <td>{{ $booking->type }}</td>
                    <td>{{ $booking->category_name }}</td>
                    <td>{{ $booking->minute }}</td>
                    <td>{{ $booking->timestart }}</td>
                    <td>{{ $booking->timeend }}</td>
                    <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">Belum ada pelanggan</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="button">
        <a href="{{ url('/calculate') }}">Kalkulasi</a>
        <a href="{{ url('/dashboard/send') }}">Kirim Notifikasi</a>
    </div>

    <button id="calculate-btn">Hitung Kalkulasi</button>
    <p id="calculation-result"></p>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("calculate-btn").addEventListener("click", function () {
            let rows = document.querySelectorAll("tbody tr");
            let bookings = [];

            // Ambil data bookings dari tabel
            rows.forEach(row => {
                let cols = row.querySelectorAll("td");
                if (cols.length > 0) {
                    let booking = {
                        name: cols[1].innerText.trim(),
                        hour: parseInt(cols[5].innerText.trim()), // Ambil durasi layanan (Jam)
                    };
                    bookings.push(booking);
                }
            });

            // Urutkan berdasarkan durasi pelayanan (Greedy SJF)
            bookings.sort((a, b) => a.hour - b.hour);

            let totalTime = 0;
            let cumulativeTime = 0;
            let resultHTML = "<strong>Urutan Pelayanan:</strong><br>";

            bookings.forEach((booking, index) => {
                cumulativeTime += booking.hour;
                totalTime += cumulativeTime;
                resultHTML += `${index + 1}. ${booking.name} - ${booking.hour} Jam (Kumulatif: ${cumulativeTime} Jam)<br>`;
            });

            resultHTML += `<br><strong>Total Waktu Pelayanan: ${totalTime} Jam</strong>`;

            // Tampilkan hasil kalkulasi
            document.getElementById("calculation-result").innerHTML = resultHTML;
        });
    });
</script>
@endsection
