<!DOCTYPE html>
<html lang="ms">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{ csrf_token() }}">
    <title>Sistem Perolehan Selangor</title>

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link href="{{ asset('css/modern.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/form-components.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/button-components.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/content-card.css') }}" rel="stylesheet">
    <link href="{{ asset('css/components/badges.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" crossorigin="anonymous">
    <link href="{{ asset('packages/selectize/dist/css/selectize.default.css') }}" rel="stylesheet">
    <link href="{{ asset('packages/bootstrap-datepicker/css/datepicker3.css') }}" rel="stylesheet">
    <link href="https://cdn.datatables.net/v/dt/dt-2.3.5/datatables.min.css" rel="stylesheet" crossorigin="anonymous">

    @yield('styles')
    @stack('styles')

    <style>
        html, body {
            margin: 0;
            padding: 0;
            min-height: 100%;
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            font-size: 0.9rem;
        }
        .form-embed-wrap {
            padding: 0.75rem 1rem 1.5rem;
            max-width: 100%;
        }
    </style>
</head>

<body>
    <div class="form-embed-wrap">
        @yield('content')
    </div>

    <script src="{{ asset('js/modern.js') }}"></script>
    <script src="{{ asset('packages/bootbox/bootbox.js') }}"></script>
    <script src="{{ asset('packages/selectize/dist/js/standalone/selectize.min.js') }}"></script>
    <script src="{{ asset('js/sheepit.js') }}"></script>
    <script src="{{ asset('packages/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
    <script src="https://cdn.datatables.net/v/dt/dt-2.3.5/datatables.min.js" crossorigin="anonymous"></script>

    @include('tenders.forms._modal_embed_scripts')
    @yield('scripts')
    @stack('scripts')
</body>

</html>
