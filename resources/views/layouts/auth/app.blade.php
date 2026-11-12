{{--
    FILE: resources/views/layouts/auth/app.blade.php
    Layout for guest pages (login, forgot password...). Draws the green / gold wave background
    and centres the page content. The dashboard layout (layouts/app.blade.php) is not touched.
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin Login' }} | Federal University Dutsin-Ma</title>
    <link rel="icon" type="image/png" href="{{asset("images/logo.jpg")}}" style="border-radius: 50%">


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif; }
    </style>
</head>

<body class="relative min-h-screen overflow-x-hidden bg-gradient-to-br from-white via-[#f4faf6] to-[#e3f1e8] antialiased">

    {{-- =====================================================================
         BACKGROUND (decoration only, hidden from screen readers)
    ====================================================================== --}}
    <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden" aria-hidden="true">

        {{-- soft diagonal light bands --}}
        <div class="absolute -left-24 bottom-0 h-[140%] w-24 -rotate-[35deg] bg-white/60 blur-sm"></div>
        <div class="absolute -right-10 top-0 h-[140%] w-28 -rotate-[35deg] bg-white/70 blur-sm"></div>

        {{-- big pale circle at the bottom left --}}
        <div class="absolute -bottom-40 -left-24 h-96 w-[34rem] rounded-full bg-[#0a5c2e]/5"></div>

        {{-- gear, left --}}
        <svg class="absolute -left-16 top-1/2 h-72 w-72 -translate-y-1/2 text-[#0a5c2e]/[0.07]" viewBox="0 0 100 100" fill="currentColor">
            <path fill-rule="evenodd" d="M44 4h12l2 9a37 37 0 0 1 8 3.300l8-5 8.500 8.500-5 8a37 37 0 0 1 3.300 8l9 2v12l-9 2a37 37 0 0 1-3.300 8l5 8-8.500 8.500-8-5a37 37 0 0 1-8 3.300l-2 9H44l-2-9a37 37 0 0 1-8-3.300l-8 5-8.500-8.500 5-8a37 37 0 0 1-3.300-8l-9-2V44l9-2a37 37 0 0 1 3.300-8l-5-8L18 17.500l8 5a37 37 0 0 1 8-3.300ZM50 33a17 17 0 1 0 0 34 17 17 0 0 0 0-34Z"/>
        </svg>

        {{-- gears, right --}}
        <svg class="absolute -right-20 top-[22%] h-72 w-72 text-[#0a5c2e]/[0.08]" viewBox="0 0 100 100" fill="currentColor">
            <path fill-rule="evenodd" d="M44 4h12l2 9a37 37 0 0 1 8 3.300l8-5 8.500 8.500-5 8a37 37 0 0 1 3.300 8l9 2v12l-9 2a37 37 0 0 1-3.300 8l5 8-8.500 8.500-8-5a37 37 0 0 1-8 3.300l-2 9H44l-2-9a37 37 0 0 1-8-3.300l-8 5-8.500-8.500 5-8a37 37 0 0 1-3.300-8l-9-2V44l9-2a37 37 0 0 1 3.300-8l-5-8L18 17.500l8 5a37 37 0 0 1 8-3.300ZM50 33a17 17 0 1 0 0 34 17 17 0 0 0 0-34Z"/>
        </svg>
        <svg class="absolute -right-4 top-[58%] h-44 w-44 text-[#0a5c2e]/[0.07]" viewBox="0 0 100 100" fill="currentColor">
            <path fill-rule="evenodd" d="M44 4h12l2 9a37 37 0 0 1 8 3.300l8-5 8.500 8.500-5 8a37 37 0 0 1 3.300 8l9 2v12l-9 2a37 37 0 0 1-3.300 8l5 8-8.500 8.500-8-5a37 37 0 0 1-8 3.300l-2 9H44l-2-9a37 37 0 0 1-8-3.300l-8 5-8.500-8.500 5-8a37 37 0 0 1-3.300-8l-9-2V44l9-2a37 37 0 0 1 3.300-8l-5-8L18 17.500l8 5a37 37 0 0 1 8-3.300ZM50 33a17 17 0 1 0 0 34 17 17 0 0 0 0-34Z"/>
        </svg>

        {{-- top-left wave: dark green + gold edge --}}
        <svg class="absolute left-0 top-0 h-[55vh] w-[48vw] min-w-[260px]" viewBox="0 0 600 520" preserveAspectRatio="none" fill="none">
            <path d="M0 0H600C520 60 420 90 330 120C190 165 80 250 0 380Z" fill="#c9a867"/>
            <path d="M0 0H570C490 55 395 85 305 112C170 152 62 232 0 340Z" fill="#14733a"/>
            <path d="M0 0H430C360 40 280 66 205 88C110 116 40 170 0 240Z" fill="#0a5c2e"/>
            <path d="M0 0H250C190 30 120 50 60 75C30 88 10 100 0 112Z" fill="#073d1f"/>
        </svg>

        {{-- bottom-right wave: gold edge + greens --}}
        <svg class="absolute bottom-0 right-0 h-[48vh] w-[46vw] min-w-[240px]" viewBox="0 0 600 450" preserveAspectRatio="none" fill="none">
            <path d="M600 40C560 120 470 170 380 200C260 240 150 330 90 450H600Z" fill="#c9a867"/>
            <path d="M600 75C558 150 468 196 378 226C262 264 168 345 120 450H600Z" fill="#14733a"/>
            <path d="M600 190C540 230 450 250 380 290C320 325 290 390 280 450H600Z" fill="#0a5c2e"/>
            <path d="M600 290C560 310 500 330 470 370C455 390 448 420 446 450H600Z" fill="#073d1f"/>
        </svg>
    </div>

    {{-- =====================================================================
         PAGE CONTENT (the Livewire login view is placed here)
    ====================================================================== --}}
    <main class="relative z-10 flex min-h-screen items-center justify-center px-4 py-10 sm:px-6">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>