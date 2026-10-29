
{{-- 
    FILE: resources/views/livewire/dashboard.blade.php
    Livewire requires ONE root element, so everything is inside this single <div>.
--}}
<div x-data="{ sidebarOpen: false }" class="min-h-screen">

    {{-- =====================================================================
         0. DATA (all numbers are placeholders. Replace with your database
            values from app/Livewire/Dashboard.php later)
    ====================================================================== --}}
    @php
        // ---- SVG icon paths (Heroicons outline, no package needed) ----
        $icons = [
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
            'check'    => 'M4.5 12.75l6 6 9-13.5',
            'x'        => 'M6 18L18 6M6 6l12 12',
            'clock'    => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z',
            'up'       => 'M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18',
            'down'     => 'M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3',
            'right'    => 'M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3',
            'chevron'  => 'M19.5 8.25l-7.5 7.5-7.5-7.5',
        ];

        // ---- Sidebar menu ----
        $menu = [
            ['label' => 'Dashboard',         'icon' => 'home',     'href' => '#', 'active' => true],
            ['label' => 'Applicants',        'icon' => 'users',    'href' => '#', 'active' => false],
            ['label' => 'Applications',      'icon' => 'document', 'href' => '#', 'active' => false],
            ['label' => 'Programmes',        'icon' => 'cap',      'href' => '#', 'active' => false],
            ['label' => 'Departments',       'icon' => 'library',  'href' => '#', 'active' => false],
            ['label' => 'Faculties',         'icon' => 'office',   'href' => '#', 'active' => false],
            ['label' => 'Academic Sessions', 'icon' => 'calendar', 'href' => '#', 'active' => false],
            ['label' => 'Reports',           'icon' => 'chart',    'href' => '#', 'active' => false],
            ['label' => 'Settings',          'icon' => 'settings', 'href' => '#', 'active' => false],
        ];

        // ---- Top 5 stat cards ----
        // 'color' decides if the % is green (good) or red (bad)
        $stats = [
            ['label' => 'Total Applicants',     'value' => 1000, 'change' => '12%', 'dir' => 'up',   'color' => 'green', 'icon' => 'users',    'card' => 'bg-[#e9f5ee] border-[#d3eadb]', 'iconBg' => 'bg-[#0a5c2e]'],
            ['label' => 'Total Applications',   'value' => 1487, 'change' => '15%', 'dir' => 'up',   'color' => 'green', 'icon' => 'document', 'card' => 'bg-[#faf3ea] border-[#f0e4d2]', 'iconBg' => 'bg-[#b8923e]'],
            ['label' => 'Admitted Applications','value' => 892,  'change' => '18%', 'dir' => 'up',   'color' => 'green', 'icon' => 'check',    'card' => 'bg-[#e9f5ee] border-[#d3eadb]', 'iconBg' => 'bg-[#0a5c2e]'],
            ['label' => 'Rejected Applications','value' => 312,  'change' => '6%',  'dir' => 'up',   'color' => 'red',   'icon' => 'x',        'card' => 'bg-[#faf3ea] border-[#f0e4d2]', 'iconBg' => 'bg-[#b8923e]'],
            ['label' => 'Pending Applications', 'value' => 283,  'change' => '10%', 'dir' => 'down', 'color' => 'green', 'icon' => 'clock',    'card' => 'bg-[#e9f5ee] border-[#d3eadb]', 'iconBg' => 'bg-[#0a5c2e]'],
        ];

        // ---- Applications by Academic Session (bar chart) ----
        $sessions = [
            'labels'       => ['2022/2023', '2023/2024', '2024/2025', '2025/2026'],
            'applications' => [320, 385, 421, 361],
            'admitted'     => [210, 265, 298, 256],
        ];

        // ---- Applications by Faculty (doughnut) ----
        $faculties = [
            ['name' => 'Faculty of Computing',            'count' => 416, 'color' => '#0a5c2e'],
            ['name' => 'Faculty of Engineering',          'count' => 267, 'color' => '#b8923e'],
            ['name' => 'Faculty of Science',              'count' => 238, 'color' => '#4fa86f'],
            ['name' => 'Faculty of Management Sciences',  'count' => 208, 'color' => '#e3d3b5'],
            ['name' => 'Faculty of Social Sciences',      'count' => 178, 'color' => '#2b2f2e'],
        ];
        $totalApplications = collect($faculties)->sum('count'); // 1,307 here: use your real total

        // ---- Gender distribution ----
        $male   = 580;
        $female = 420;
        $genderTotal = $male + $female;

        // ---- Most applied-for programmes ----
        $programmes = [
            ['name' => 'B.Sc. Computer Science',         'count' => 242, 'percent' => 16],
            ['name' => 'B.Sc. Software Engineering',     'count' => 198, 'percent' => 13],
            ['name' => 'B.Eng. Civil Engineering',       'count' => 156, 'percent' => 10],
            ['name' => 'B.Sc. Accounting',               'count' => 142, 'percent' => 9],
            ['name' => 'B.Sc. Business Administration',  'count' => 118, 'percent' => 8],
        ];
        $maxProgramme = collect($programmes)->max('count');

        // ---- Programme capacity ----
        $capacity  = 4850;
        $admitted  = 3781;
        $remaining = $capacity - $admitted;
        $utilisation = round(($admitted / $capacity) * 100);

        // ---- Recent applications table ----
        $recent = [
            ['no' => 'APPN0001245', 'name' => 'Usman Ahmed',     'programme' => 'B.Sc. Computer Science',       'session' => '2025/2026', 'status' => 'Admitted', 'date' => '2025-04-18'],
            ['no' => 'APPN0001244', 'name' => 'Aisha Ibrahim',   'programme' => 'B.Eng. Electrical Engineering','session' => '2025/2026', 'status' => 'Pending',  'date' => '2025-04-17'],
            ['no' => 'APPN0001243', 'name' => 'Musa Bello',      'programme' => 'B.Sc. Accounting',             'session' => '2025/2026', 'status' => 'Rejected', 'date' => '2025-04-16'],
            ['no' => 'APPN0001242', 'name' => 'Fatima Sani',     'programme' => 'B.Sc. Mathematics',            'session' => '2025/2026', 'status' => 'Admitted', 'date' => '2025-04-15'],
            ['no' => 'APPN0001241', 'name' => 'Ibrahim Yusuf',   'programme' => 'B.Sc. Business Administration','session' => '2025/2026', 'status' => 'Pending',  'date' => '2025-04-14'],
        ];
        $statusClass = [
            'Admitted' => 'bg-green-100 text-green-800 border-green-300',
            'Pending'  => 'bg-yellow-100 text-yellow-800 border-yellow-300',
            'Rejected' => 'bg-red-100 text-red-700 border-red-300',
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

        {{-- 2b. Menu links --}}
        <nav class="flex-1 space-y-1 px-3 pt-2">
            @foreach ($menu as $item)
                <a href="{{ $item['href'] }}"
                   class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition
                          {{ $item['active'] ? 'bg-[#0f7a3d] text-white shadow' : 'text-green-50/90 hover:bg-white/10' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[$item['icon']] }}" />
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
         3. MAIN AREA (everything to the right of the sidebar)
    ====================================================================== --}}
    <div class="lg:pl-64">

        {{-- ================================================================
             3a. TOP BAR (university name, notification bell, admin dropdown)
        ================================================================= --}}
        <header class="sticky top-0 z-20 flex items-center justify-between border-b border-slate-200 bg-white px-4 py-3 sm:px-6">
            <div class="flex items-center gap-3">
                {{-- Hamburger (phones only) --}}
                <button @click="sidebarOpen = true" class="rounded-md p-2 text-slate-600 hover:bg-slate-100 lg:hidden">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['menu'] }}" />
                    </svg>
                </button>

                <img src="{{ asset('images/logo.jpg') }}" alt="" class="hidden h9 w-9 rounded-full object-contain sm:block"
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
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['bell'] }}" />
                    </svg>
                    <span class="absolute right-1 top-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-semibold text-white">3</span>
                </button>

                {{-- Admin dropdown (Alpine.js) --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-[#0a5c2e] text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['user'] }}" />
                            </svg>
                        </span>
                        <span class="hidden text-left leading-tight sm:block">
                            <span class="block text-sm font-semibold text-slate-900">Admin</span>
                            <span class="block text-xs text-slate-500">Administrator</span>
                        </span>
                        <svg class="hidden h-4 w-4 text-slate-500 sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['chevron'] }}" />
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


        <main class="space-y-5 p-4 sm:p-6">

            {{-- ============================================================
                 3b. WELCOME MESSAGE + DATE
            ============================================================= --}}
            <section class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Welcome back, Admin 👋</h1>
                    <p class="text-sm text-slate-500">Here's what's happening at Federal University Dutsin-Ma</p>
                </div>
                <div class="flex items-center gap-2 text-sm text-slate-600">
                    <svg class="h-6 w-6 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['calendar'] }}" />
                    </svg>
                    <div class="leading-tight">
                        <p>{{ now()->format('l, d F Y') }}</p>
                        <p>{{ now()->format('h:i A') }}</p>
                    </div>
                </div>
            </section>


            {{-- ============================================================
                 3c. STAT CARDS (5 cards on top)
            ============================================================= --}}
            <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
                @foreach ($stats as $stat)
                    <div class="rounded-xl border p-4 {{ $stat['card'] }}">
                        <div class="flex items-center gap-4">
                            <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl text-white {{ $stat['iconBg'] }}">
                                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[$stat['icon']] }}" />
                                </svg>
                            </span>
                            <div>
                                <p class="text-xs font-medium text-slate-700">{{ $stat['label'] }}</p>
                                <p class="text-3xl font-bold text-slate-900">{{ number_format($stat['value']) }}</p>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center gap-2 text-xs">
                            <span class="flex items-center gap-1 font-semibold {{ $stat['color'] === 'green' ? 'text-green-700' : 'text-red-600' }}">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[$stat['dir']] }}" />
                                </svg>
                                {{ $stat['change'] }}
                            </span>
                            <span class="text-slate-500">vs last session</span>
                        </div>
                    </div>
                @endforeach
            </section>


            {{-- ============================================================
                 3d. CHARTS ROW: bar chart (left) + faculty doughnut (right)
            ============================================================= --}}
            <section class="grid grid-cols-1 gap-5 xl:grid-cols-2">

                {{-- 3d-1. Applications by Academic Session (bar chart) --}}
                <div class="rounded-xl border border-slate-200 bg-white p-5">
                    <div class="flex items-center justify-between">
                        <h2 class="font-semibold text-slate-900">Applications by Academic Session</h2>
                        <select class="rounded-md border border-slate-300 bg-white px-2 py-1 text-xs text-slate-600">
                            <option>All Sessions</option>
                            @foreach ($sessions['labels'] as $label)
                                <option>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-4 h-64" wire:ignore>
                        <canvas id="sessionChart"></canvas>
                    </div>
                </div>

                {{-- 3d-2. Applications by Faculty (doughnut chart + legend) --}}
                <div class="rounded-xl border border-slate-200 bg-white p-5">
                    <h2 class="font-semibold text-slate-900">Applications by Faculty</h2>

                    {{-- Pie chart on the left, write-up on the right (stacks only on phones) --}}
                    <div class="mt-4 flex flex-col items-center gap-5 sm:flex-row">
                        {{-- Doughnut with centre text --}}
                        <div class="relative h-40 w-40 shrink-0 xl:h-36 xl:w-36" wire:ignore>
                            <canvas id="facultyChart"></canvas>
                            <div class="pointer-events-none absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-xl font-bold text-slate-900">{{ number_format($totalApplications) }}</span>
                                <span class="text-[11px] text-slate-500">Total Applications</span>
                            </div>
                        </div>

                        {{-- Legend --}}
                        <ul class="w-full min-w-0 flex-1 space-y-2.5 text-xs">
                            @foreach ($faculties as $f)
                                <li class="flex items-center justify-between gap-3 border-b border-slate-100 pb-2 last:border-0">
                                    <span class="flex min-w-0 items-center gap-2 text-slate-700">
                                        <span class="h-3 w-3 shrink-0 rounded-full" style="background: {{ $f['color'] }}"></span>
                                        {{ $f['name'] }}
                                    </span>
                                    <span class="whitespace-nowrap text-slate-700">
                                        {{ $f['count'] }}
                                        <span class="ml-1 text-slate-500">({{ round(($f['count'] / $totalApplications) * 100) }}%)</span>
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </section>


            {{-- ============================================================
                 3e. THIRD ROW: gender + top programmes + capacity
            ============================================================= --}}
            <section class="grid grid-cols-1 gap-5 lg:grid-cols-3">

                {{-- 3e-1. Applicant Distribution by Gender (pie chart) --}}
                <div class="rounded-xl border border-slate-200 bg-white p-5">
                    <h2 class="font-semibold text-slate-900">Applicant Distribution by Gender</h2>
                    <div class="mt-4 flex items-center gap-6">
                        <div class="h-36 w-36 shrink-0" wire:ignore>
                            <canvas id="genderChart"></canvas>
                        </div>
                        <ul class="space-y-4 text-sm">
                            <li class="flex items-start gap-2">
                                <span class="mt-1 h-3 w-3 rounded-full bg-[#0a5c2e]"></span>
                                <div>
                                    <p class="font-medium text-slate-800">Male</p>
                                    <p class="text-slate-600">{{ $male }} ({{ round($male / $genderTotal * 100) }}%)</p>
                                </div>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="mt-1 h-3 w-3 rounded-full bg-[#c9a867]"></span>
                                <div>
                                    <p class="font-medium text-slate-800">Female</p>
                                    <p class="text-slate-600">{{ $female }} ({{ round($female / $genderTotal * 100) }}%)</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- 3e-2. Most Applied-for Programmes (progress bars) --}}
                <div class="rounded-xl border border-slate-200 bg-white p-5">
                    <h2 class="font-semibold text-slate-900">Most Applied-for Programmes</h2>
                    <ul class="mt-4 space-y-4">
                        @foreach ($programmes as $p)
                            <li class="grid grid-cols-12 items-center gap-2 text-xs">
                                <span class="col-span-5 text-slate-700">{{ $p['name'] }}</span>
                                <div class="col-span-5 h-2 overflow-hidden rounded-full bg-slate-200">
                                    <div class="h-full rounded-full bg-[#0a5c2e]" style="width: {{ ($p['count'] / $maxProgramme) * 100 }}%"></div>
                                </div>
                                <span class="col-span-2 text-right text-slate-700">{{ $p['count'] }} ({{ $p['percent'] }}%)</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- 3e-3. Programme Capacity Utilisation (ring chart) --}}
                <div class="rounded-xl border border-slate-200 bg-white p-5">
                    <h2 class="font-semibold text-slate-900">Programme Capacity Utilisation</h2>
                    <div class="mt-4 flex items-center gap-6">
                        <div class="text-center">
                            <div class="relative mx-auto h-32 w-32" wire:ignore>
                                <canvas id="capacityChart"></canvas>
                                <div class="pointer-events-none absolute inset-0 flex items-center justify-center">
                                    <span class="text-xl font-bold text-slate-900">{{ $utilisation }}%</span>
                                </div>
                            </div>
                            <p class="mt-2 text-xs text-slate-600">Overall Utilisation</p>
                        </div>
                        <dl class="flex-1 space-y-4 text-sm">
                            <div class="flex justify-between"><dt class="text-slate-700">Total Capacity</dt><dd class="font-semibold">{{ number_format($capacity) }}</dd></div>
                            <div class="flex justify-between"><dt class="text-slate-700">Total Admitted</dt><dd class="font-semibold">{{ number_format($admitted) }}</dd></div>
                            <div class="flex justify-between"><dt class="text-slate-700">Remaining</dt><dd class="font-semibold">{{ number_format($remaining) }}</dd></div>
                        </dl>
                    </div>
                </div>
            </section>


            {{-- ============================================================
                 3f. RECENT APPLICATIONS TABLE
            ============================================================= --}}
            <section class="rounded-xl border border-slate-200 bg-white p-5">
                <div class="flex items-center justify-between">
                    <h2 class="font-semibold text-slate-900">Recent Applications</h2>
                    <a href="#" class="flex items-center gap-1 text-xs font-medium text-[#0a5c2e] underline">
                        View All
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['right'] }}" />
                        </svg>
                    </a>
                </div>

                <div class="mt-3 overflow-x-auto">
                    <table class="w-full min-w-[700px] text-left text-xs">
                        <thead class="bg-slate-50 text-slate-700">
                            <tr>
                                <th class="px-3 py-2 font-semibold">Application No.</th>
                                <th class="px-3 py-2 font-semibold">Applicant Name</th>
                                <th class="px-3 py-2 font-semibold">Programme</th>
                                <th class="px-3 py-2 font-semibold">Academic Session</th>
                                <th class="px-3 py-2 font-semibold">Status</th>
                                <th class="px-3 py-2 font-semibold">Application Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($recent as $row)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-3 py-3 text-[#0a5c2e]">{{ $row['no'] }}</td>
                                    <td class="px-3 py-3 text-slate-700">{{ $row['name'] }}</td>
                                    <td class="px-3 py-3 text-slate-700">{{ $row['programme'] }}</td>
                                    <td class="px-3 py-3 text-slate-700">{{ $row['session'] }}</td>
                                    <td class="px-3 py-3">
                                        <span class="rounded-full border px-3 py-0.5 font-medium {{ $statusClass[$row['status']] }}">{{ $row['status'] }}</span>
                                    </td>
                                    <td class="px-3 py-3 text-slate-700">{{ $row['date'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
           

        </main>
    </div>


    {{-- =====================================================================
         4. CHART SCRIPTS (Chart.js). @script is a Livewire 3 directive.
    ====================================================================== --}}
    @script
    <script>
        const green = '#0a5c2e', gold = '#b8923e', tan = '#c9a867';

        // 4a. Bar chart: Applications by Academic Session
        new Chart(document.getElementById('sessionChart'), {
            type: 'bar',
            data: {
                labels: @js($sessions['labels']),
                datasets: [
                    { label: 'Applications', data: @js($sessions['applications']), backgroundColor: green, borderRadius: 2 },
                    { label: 'Admitted',     data: @js($sessions['admitted']),     backgroundColor: tan,   borderRadius: 2 },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } } },
                scales: { y: { beginAtZero: true, grid: { color: '#eef0f2' } }, x: { grid: { display: false } } },
            },
        });

        // 4b. Doughnut: Applications by Faculty
        new Chart(document.getElementById('facultyChart'), {
            type: 'doughnut',
            data: {
                labels: @js(collect($faculties)->pluck('name')),
                datasets: [{ data: @js(collect($faculties)->pluck('count')), backgroundColor: @js(collect($faculties)->pluck('color')), borderWidth: 2, borderColor: '#fff' }],
            },
            options: { responsive: true, maintainAspectRatio: false, cutout: '60%', plugins: { legend: { display: false } } },
        });

        // 4c. Pie: Gender distribution
        new Chart(document.getElementById('genderChart'), {
            type: 'pie',
            data: {
                labels: ['Male', 'Female'],
                datasets: [{ data: [{{ $male }}, {{ $female }}], backgroundColor: [green, tan], borderWidth: 2, borderColor: '#fff' }],
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } },
        });

        // 4d. Ring: Capacity utilisation
        new Chart(document.getElementById('capacityChart'), {
            type: 'doughnut',
            data: {
                datasets: [{ data: [{{ $admitted }}, {{ $remaining }}], backgroundColor: [green, '#ede3cf'], borderWidth: 0 }],
            },
            options: { responsive: true, maintainAspectRatio: false, cutout: '72%', plugins: { legend: { display: false }, tooltip: { enabled: false } } },
        });
    </script>
    @endscript

</div>
