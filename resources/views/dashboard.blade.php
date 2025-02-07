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
    <title>Dashboard</title>
</head>
<body>
    <div class="topnav">
        <div class="items">
            <h3>Dashboard</h3>
            <div class="login">
                <p>Admin</p>
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
                    <th>Name</th>
                    <th>Email</th>
                    <th>Type Motor</th>
                    <th>Category</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bookings as $booking)
                    <tr>
                        <td>{{ $booking->name }}</td>
                        <td>{{ $booking->email }}</td>
                        <td>{{ $booking->type }}</td>
                        <td>{{ $booking->category->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="button">
            <button>Kalkulasi</button>
            <button>Kirim Notifikasi</button>
        </div>
    </div>
</body>
</html>