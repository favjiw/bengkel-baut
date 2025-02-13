<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<div class="topnav">
    <div class="items">
        <div class="logo"><img src="{{ asset('images/Baut.png') }}" alt=""></div>
        <div class="login">
            <a href="{{ url('/login') }}">Login</a>
        </div>
    </div>
</div>
<body>
    <div class="bannerimage">
        <img src="{{ asset('images/Baut2.jpg') }}" alt="">
    </div>
    <h1 style="text-align: center">Kami menyediakan:</h1>
    <div class="packets">
        @foreach ($categories as $category)
            <div class="packet">
                <img src="https://kpssteel.com/storage/2022/10/perbedaan-baut-dan-sekrup-KPS-Steel-distributor-besi-jakarta-1024x536.jpg" alt="">
                <h3>{{ $category->name }}</h3>
                <p>{{ $category->description }}</p>
                <p>Estimasi Penegerjaan : {{ $category->minute }} Menit</p>
                <p><b>Rp.{{ number_format($category->price, 0, ',', '.') }}</b></p>
            </div>
        @endforeach
    </div>
    <div class="button">
        <a href="{{ url('/booking') }}">Pesan Sekarang</a>
    </div>
</body>
</html>