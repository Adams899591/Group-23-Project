{{-- 
    FILE: resources/views/components/layouts/app.blade.php
    This is the main layout. Every page is injected into {{ $slot }}.
    The SIDEBAR lives here, so all pages get it automatically.
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dashboard' }} | Federal University Dutsin-Ma</title>

    {{-- Font (Inter) --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">

    {{-- Tailwind CSS (comes from Vite: resources/css/app.css) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Chart.js (used for the bar chart and the doughnut/pie charts) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    {{-- Livewire styles --}}
    @livewireStyles

    <style>
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>

{{-- 
    sidebarOpen lives on <body> so the sidebar (here) and the hamburger button
    (in the top bar, also here in the layout) share the same state on phones.
--}}
<body x-data="{ sidebarOpen: false }" class="bg-[#f4f7f5] text-slate-800 antialiased">

    {{-- =====================================================================
         SIDEBAR DATA (menu links and icons)
         Change 'href' to your real URLs / route('...') and 'pattern' to the
         URL pattern used to highlight the active link.
    ====================================================================== --}}
    @php
        $sidebarIcons = [
            'home'     => 'M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25',
            'users'    => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z',
            'document' => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z',
            'cap'      => 'M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5',
            'library'  => 'M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z',
            'office'   => 'M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z',
            'calendar' => 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5',
            'chart'    => 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z',
            'settings' => 'M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75',
            'bell'     => 'M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0',
            'user'     => 'M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z',
            'menu'     => 'M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5',
            'chevron'  => 'M19.5 8.25l-7.5 7.5-7.5-7.5',
        ];

        $menu = [
            ['label' => 'Dashboard',         'icon' => 'home',     'href' => 'dashboard',         'pattern' => 'dashboard*'],
            ['label' => 'Applicants',        'icon' => 'users',    'href' => 'applicants',        'pattern' => 'applicants*'],
            ['label' => 'Applications',      'icon' => 'document', 'href' => 'applicants',      'pattern' => 'applications*'],
            ['label' => 'Programmes',        'icon' => 'cap',      'href' => 'programmes',        'pattern' => 'programmes*'],
            ['label' => 'Departments',       'icon' => 'library',  'href' => 'programmes',       'pattern' => 'departments*'],
            ['label' => 'Faculties',         'icon' => 'office',   'href' => 'programmes',         'pattern' => 'faculties*'],
            ['label' => 'Academic Sessions', 'icon' => 'calendar', 'href' => 'academicSessions', 'pattern' => 'academicSessions*'],
            ['label' => 'Reports',           'icon' => 'chart',    'href' => 'dashboard',           'pattern' => 'reports*'],
            ['label' => 'Settings',          'icon' => 'settings', 'href' => 'dashboard',          'pattern' => 'settings*'],
        ];
    @endphp


    {{-- =====================================================================
         1. MOBILE OVERLAY (dark background when the sidebar is open on phones)
    ====================================================================== --}}
    <div x-show="sidebarOpen" x-cloak x-transition.opacity @click="sidebarOpen = false"
         class="fixed inset-0 z-30 bg-black/50 lg:hidden"></div>


    {{-- =====================================================================
         2. SIDEBAR
    ====================================================================== --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-40 flex w-64 flex-col overflow-hidden bg-gradient-to-b from-[#0a5c2e] via-[#06431f] to-[#032a14] text-white transition-transform duration-200 lg:translate-x-0">

        {{-- 2a. Logo --}}
        <div class="flex justify-center px-4 pb-4 pt-4">
            {{-- Put your logo in public/images/logo.jpg --}}
            <img src="{{ asset('images/logo.jpg') }}" alt="FUDMA Logo"
                 class="h-20 w-20 rounded-full bg-white object-contain p-1"
                 onerror="this.style.display='none'">
        </div>

        {{-- 2b. Menu links (the active one is highlighted automatically) --}}
        <nav class="flex-1 space-y-1 px-3 pt-2">
            @foreach ($menu as $item)
                @php $active = request()->is($item['pattern']); @endphp
                <a href="{{ route($item['href']) }}"
                   class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition
                          {{ $active ? 'bg-[#0f7a3d] text-white shadow' : 'text-green-50/90 hover:bg-white/10' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $sidebarIcons[$item['icon']] }}" />
                    </svg>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        {{-- 2c. Motto at the bottom --}}
        <div class="px-6 pb-8 pt-6 text-sm italic leading-snug text-green-100/70">
            Building a brighter<br>future through quality<br>education
        </div>
    </aside>


    {{-- =====================================================================
         3. RIGHT SIDE (top bar + page content, pushed right of the sidebar on desktop)
    ====================================================================== --}}
    <div class="lg:pl-64">

        {{-- ================================================================
             TOP BAR (university name, notification bell, admin dropdown)
        ================================================================= --}}
        <header class="sticky top-0 z-20 flex items-center justify-between border-b border-slate-200 bg-white px-4 py-3 sm:px-6">
            <div class="flex items-center gap-3">
                {{-- Hamburger (phones only) --}}
                <button @click="sidebarOpen = true" class="rounded-md p-2 text-slate-600 hover:bg-slate-100 lg:hidden">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $sidebarIcons['menu'] }}" />
                    </svg>
                </button>

                <img src="{{ asset('images/logo.jpg') }}" alt="" class="hidden h-9 w-9 rounded-full object-contain sm:block"
                     onerror="this.style.display='none'">
                <div class="leading-tight">
                    <p class="text-sm font-bold text-slate-900 sm:text-base">Federal University Dutsin-Ma</p>
                    <p class="text-xs text-slate-500">Integrity &amp; Service</p>
                </div>
            </div>

            <div class="flex items-center gap-4 sm:gap-6">
                {{-- Notification bell --}}
                <button class="relative rounded-full p-2 text-slate-600 hover:bg-slate-100">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $sidebarIcons['bell'] }}" />
                    </svg>
                    <span class="absolute right-1 top-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-semibold text-white">3</span>
                </button>

                {{-- Admin dropdown (Alpine.js) --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-[#0a5c2e] text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $sidebarIcons['user'] }}" />
                            </svg>
                        </span>
                        <span class="hidden text-left leading-tight sm:block">
                            <span class="block text-sm font-semibold text-slate-900">Admin</span>
                            <span class="block text-xs text-slate-500">Administrator</span>
                        </span>
                        <svg class="hidden h-4 w-4 text-slate-500 sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $sidebarIcons['chevron'] }}" />
                        </svg>
                    </button>

                    <div x-show="open" x-cloak x-transition
                         class="absolute right-0 mt-2 w-44 rounded-lg border border-slate-200 bg-white py-1 shadow-lg">
                        <a href="#" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Profile</a>
                        <a href="#" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Settings</a>
                        <form method="POST" action="#">
                            @csrf
                            <button class="block w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-slate-50">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- The page itself (dashboard, applicants, ...) --}}
        {{ $slot }}
    </div>

    {{-- Livewire scripts (Livewire 3 already includes Alpine.js, so do NOT load Alpine again) --}}
    @livewireScripts
</body>
</html>