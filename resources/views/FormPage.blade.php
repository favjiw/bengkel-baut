<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <title>Booking</title>
</head>
<body>
    <div class="header">
        <h1>Booking Form</h1>
    </div>
    <div class="form">
        <form action="{{ route('booking.store') }}" method="POST">
            @csrf
            <label for="name">Name</label>
            <input type="text" name="name" id="name">
            <label for="phone">Phone</label>
            <input type="phone" name="phone" id="phone">
            <label for="type">Type Motor</label>
            <input type="text" name="type" id="type">
            <label for="category_id">Pilih Paket:</label>
            <select name="category_id" id="category_id">
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>