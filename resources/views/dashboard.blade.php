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
        <a href="{{ url('/dashboard/send?booking_date=' . request('booking_date', now()->toDateString())) }}">Kirim Notifikasi</a>
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
            // Ambil data dari tabel booking tanpa greedy dan dengan greedy
            let rowsUnsorted = document.querySelectorAll("#bookings_unsorted tbody tr");
            let rowsSorted = document.querySelectorAll("#bookings tbody tr");

            let bookingsUnsorted = [];
            let bookingsSorted = [];

            // Menyimpan Data Booking ke Array bookingsUnsorted
            rowsUnsorted.forEach(row => {
                let cols = row.querySelectorAll("td");
                if (cols.length > 0 && cols[5].innerText !== "Belum ada pelanggan") {
                    let booking = {
                        name: cols[1].innerText,
                        duration: parseInt(cols[5].innerText)
                    };
                    bookingsUnsorted.push(booking);
                }
            });

            // Menyimpan Data Booking ke Array bookingsSorted
            rowsSorted.forEach(row => {
                let cols = row.querySelectorAll("td");
                if (cols.length > 0 && cols[5].innerText !== "Belum ada pelanggan") {
                    let booking = {
                        name: cols[1].innerText,
                        duration: parseInt(cols[5].innerText)
                    };
                    bookingsSorted.push(booking);
                }
            });

            // mengurutkan bookingSorted
            for (let i = 0; i < bookingsSorted.length - 1; i++) {
                let minIndex = i;
                for (let j = i + 1; j < bookingsSorted.length; j++) {
                    if (bookingsSorted[j].duration < bookingsSorted[minIndex].duration) {
                        minIndex = j;
                    }
                }
                // Tukar posisi jika ditemukan nilai yang lebih kecil
                if (minIndex !== i) {
                    let temp = bookingsSorted[i];
                    bookingsSorted[i] = bookingsSorted[minIndex];
                    bookingsSorted[minIndex] = temp;
                }
            }

            // Hitung waktu kumulatif untuk bookingsUnsorted (tanpa greedy)
            let totalTimeUnsorted = 0;
            let cumulativeTimeUnsorted = 0;
            let unsortedText = "<strong>Urutan Pelayanan Tanpa Greedy:</strong><br>";

            bookingsUnsorted.forEach((booking, index) => {
                cumulativeTimeUnsorted += booking.duration;
                totalTimeUnsorted += cumulativeTimeUnsorted;
                unsortedText += `${index + 1}. ${booking.name} - ${booking.duration} Menit (Kumulatif: ${cumulativeTimeUnsorted} Menit)<br>`;
            });

            unsortedText += `<br><strong>Total Waktu Pelayanan: ${totalTimeUnsorted} Menit</strong>`;

            // Hitung waktu kumulatif untuk bookingsSorted (setelah greedy)
            let totalTimeSorted = 0;
            let cumulativeTimeSorted = 0;
            let sortedText = "<strong>Urutan Pelayanan Dengan Greedy:</strong><br>";

            bookingsSorted.forEach((booking, index) => {
                cumulativeTimeSorted += booking.duration;
                totalTimeSorted += cumulativeTimeSorted;
                sortedText += `${index + 1}. ${booking.name} - ${booking.duration} Menit (Kumulatif: ${cumulativeTimeSorted} Menit)<br>`;
            });

            sortedText += `<br><strong>Total Waktu Pelayanan: ${totalTimeSorted} Menit</strong>`;

            // Tampilkan hasil pada <p> id unsorted dan sorted
            document.getElementById("unsorted").innerHTML = unsortedText;
            document.getElementById("sorted").innerHTML = sortedText;
        });
    });


</script>
@endsection
