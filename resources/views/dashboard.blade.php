<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=account_circle" />
    <title>Dashboard</title>
</head>
<body>
    <div class="topnav">
        <div class="items">
            <h3>Dashboard</h3>
            <div class="logout">
                <p class="dropbtn">Admin</p>
                <div class="dropdown">
                    <a href="#">Profile</a>
                    <a href="#" style="color: red" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <h2>Booking List</h2>
        <div class="date-button">
            <input type="date" name="date" id="date">
            <button type="submit">Search</button>
        </div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Type Motor</th>
                    <th>Category</th>
                    <th>Time Start</th>
                    <th>Time End</th>
                    <th>Hour</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bookings as $booking)
                    <tr>
                        <td>{{ $booking->id }}</td>
                        <td>{{ $booking->name }}</td>
                        <td>{{ $booking->phone }}</td>
                        <td>{{ $booking->type }}</td>
                        <td>{{ $booking->category_name }}</td>
                        <td>{{ $booking->timestart }}</td>
                        <td>{{ $booking->timeend }}</td>
                        <td>{{ $booking->hour }} Jam</td>
                        <td>{{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <p>Belum ada pelanggan</p>
                @endforelse
            </tbody>
        </table>
        <div class="button">
            <a href="{{ url('/calculate') }}">Kalkulasi</a>
            <a href="{{ url('/dashboard/send') }}">Kirim Notifikasi</a>
        </div>
        <button id="calculate-btn">Hitung Kalkulasi</button>
        <p id="calculation-result"></p>
    </div>

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
</body>
</html>