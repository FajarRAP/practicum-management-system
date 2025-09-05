<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="shortcut icon" href="{{ Vite::asset('resources/image/logo.png') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-lime-50">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-slate-100 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                    {{ $header }}

                    @if (isset($activeAcademicYear))
                        <div class="text-sm">
                            <span class="font-medium text-gray-500">Tahun Ajaran Aktif:</span>
                            <span
                                class="ms-2 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                {{ $activeAcademicYear->year }} - {{ $activeAcademicYear->semester }}
                            </span>
                        </div>
                    @else
                        <div class="text-sm">
                            <span
                                class="ms-2 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                Tidak ada tahun ajaran yang aktif
                            </span>
                        </div>
                    @endif
                </div>

            </header>
        @endisset

        <!-- Page Content -->
        <main {{ $attributes }}>
            @session('error')
                <x-danger-alert :$value />
            @endsession
            @session('success')
                <x-success-alert :$value />
            @endsession

            {{ $slot }}
        </main>
    </div>
</body>

</html>
