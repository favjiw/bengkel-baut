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

    <table id="bookings">
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

    <table id="bookings_unsorted">
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
            @forelse ($bookings_unsorted as $booking)
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
        <a href="{{ url('/calculate') }}">Atur Jadwal</a>
        <a href="{{ url('/dashboard/send') }}">Kirim Notifikasi</a>
    </div>

    <button id="calculate-btn">Hitung Waktu Pelayanan</button>
    <p id="calculation-result"></p>

    <p id="unsorted"></p>
    <p id="sorted"></p>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("calculate-btn").addEventListener("click", function () {
            let rowsUnsorted = document.querySelectorAll("#bookings_unsorted tbody tr");
            let rowsSorted = document.querySelectorAll("#bookings tbody tr");

            let bookingsUnsorted = [];
            let bookingsSorted = [];

            // Ambil data bookings_unsorted dari tabel sebelum greedy
            rowsUnsorted.forEach(row => {
                let cols = row.querySelectorAll("td");
                if (cols.length > 0 && cols[5].innerText.trim() !== "Belum ada pelanggan") {
                    let booking = {
                        name: cols[1].innerText.trim(),
                        duration: parseInt(cols[5].innerText.trim()) // Ambil lama pengerjaan (menit)
                    };
                    bookingsUnsorted.push(booking);
                }
            });

            // Ambil data bookings dari tabel setelah greedy
            rowsSorted.forEach(row => {
                let cols = row.querySelectorAll("td");
                if (cols.length > 0 && cols[5].innerText.trim() !== "Belum ada pelanggan") {
                    let booking = {
                        name: cols[1].innerText.trim(),
                        duration: parseInt(cols[5].innerText.trim()) // Ambil lama pengerjaan (menit)
                    };
                    bookingsSorted.push(booking);
                }
            });

            // Hitung waktu kumulatif untuk bookings_unsorted (tanpa greedy)
            let totalTimeUnsorted = 0;
            let cumulativeTimeUnsorted = 0;
            let unsortedText = "<strong>Urutan Pelayanan Tanpa Greedy:</strong><br>";

            bookingsUnsorted.forEach((booking, index) => {
                cumulativeTimeUnsorted += booking.duration;
                totalTimeUnsorted += cumulativeTimeUnsorted;
                unsortedText += `${index + 1}. ${booking.name} - ${booking.duration} Menit (Kumulatif: ${cumulativeTimeUnsorted} Menit)<br>`;
            });

            unsortedText += `<br><strong>Total Waktu Pelayanan: ${totalTimeUnsorted} Menit</strong>`;

            // Hitung waktu kumulatif untuk bookings (setelah greedy)
            let totalTimeSorted = 0;
            let cumulativeTimeSorted = 0;
            let sortedText = "<strong>Urutan Pelayanan Dengan Greedy:</strong><br>";

            bookingsSorted.forEach((booking, index) => {
                cumulativeTimeSorted += booking.duration;
                totalTimeSorted += cumulativeTimeSorted;
                sortedText += `${index + 1}. ${booking.name} - ${booking.duration} Menit (Kumulatif: ${cumulativeTimeSorted} Menit)<br>`;
            });

            sortedText += `<br><strong>Total Waktu Pelayanan: ${totalTimeSorted} Menit</strong>`;

            // Tampilkan hasil di elemen <p> yang sesuai
            document.getElementById("unsorted").innerHTML = unsortedText;
            document.getElementById("sorted").innerHTML = sortedText;
        });
    });

</script>
@endsection
