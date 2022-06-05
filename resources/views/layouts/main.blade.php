<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title')</title>

        <link rel="stylesheet" href="{{asset('/css/app.css?v=').time()}}">
        <link rel="stylesheet" href="{{asset('/css/custom.css?v=').time()}}">

    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-light container">
            <div class="container-fluid">
                <a href="/" class="navbar-brand fw-bold">ALGORITHM</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/">Home</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Sort</a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="/merge_sort">Merge Sort</a></li>
                                <li><a class="dropdown-item" href="quick_sort">Quick Sort</a></li>
                                <li><a class="dropdown-item" href="bucket_sort">Bucket Sort</a></li>
                                <li><a class="dropdown-item" href="heap_sort">Heap Sort</a></li>
                                <li><a class="dropdown-item" href="counting_sort">Counting Sort</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Search</a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="#">Binary Search</a></li>
                                <li><a class="dropdown-item" href="#">Quick Sort</a></li>
                                <li><a class="dropdown-item" href="#">Bucket Sort</a></li>
                                <li><a class="dropdown-item" href="#">Heap Sort</a></li>
                                <li><a class="dropdown-item" href="#">Counting Sort</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Hashing</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Dinamic programming</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Exponentiation by squaring</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container mb-4">
            <svg width="100%" height="30" viewBox="0 0 100 100" preserveAspectRatio="none">
                <polygon points="0, 0, 50, 0, 0, 50" fill="rgb(200,50,50)" />
            </svg>
        </div>
        @yield('content')
        <footer class="container my-4">
            <div class="px-4">By Orengia Christian 2022</div>
        </footer>
        <script src="/js/app.js"></script>
        @yield('scripts')
    </body>
</html>