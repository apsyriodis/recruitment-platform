<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('title')

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700,800" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <style>
        :root {
            --primary: #111827;
            --primary-light: #374151;
            --background: #f5f7fb;
            --border: #e5e7eb;
            --muted: #6b7280;
            --success: #16a34a;
            --danger: #dc2626;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: var(--background);
            color: #1f2937;
            font-family: 'Nunito', sans-serif;
        }

        .page-wrapper {
            min-height: 100vh;
            padding: 40px 20px;
        }

        .alert {
            max-width: 1200px;
            margin: 0 auto 20px;
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .05);
        }

        .form-control,
        .form-select {
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 10px 13px;
            transition: .2s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #9ca3af;
            box-shadow: 0 0 0 3px rgba(17, 24, 39, .08);
        }

        .btn {
            border-radius: 10px;
            font-weight: 700;
            padding: 10px 18px;
            transition: all .2s ease;
        }

        .btn-dark {
            background: #111827;
            border-color: #111827;
        }

        .btn-dark:hover {
            background: #000;
            border-color: #000;
            transform: translateY(-1px);
            box-shadow: 0 5px 12px rgba(0, 0, 0, .15);
        }
    </style>

    @yield('styles')
</head>

<body>

    <div class="page-wrapper">

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')

    </div>

    @yield('scripts')

</body>

</html>
