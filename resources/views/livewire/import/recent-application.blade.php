<section>

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

        // // ---- Top 5 stat cards ----
        // // 'color' decides if the % is green (good) or red (bad)
        // $stats = [
        //     ['label' => 'Total Applicants',     'value' => 1000, 'change' => '12%', 'dir' => 'up',   'color' => 'green', 'icon' => 'users',    'card' => 'bg-[#e9f5ee] border-[#d3eadb]', 'iconBg' => 'bg-[#0a5c2e]'],
        //     ['label' => 'Total Applications',   'value' => 1487, 'change' => '15%', 'dir' => 'up',   'color' => 'green', 'icon' => 'document', 'card' => 'bg-[#faf3ea] border-[#f0e4d2]', 'iconBg' => 'bg-[#b8923e]'],
        //     ['label' => 'Admitted Applications','value' => 892,  'change' => '18%', 'dir' => 'up',   'color' => 'green', 'icon' => 'check',    'card' => 'bg-[#e9f5ee] border-[#d3eadb]', 'iconBg' => 'bg-[#0a5c2e]'],
        //     ['label' => 'Rejected Applications','value' => 312,  'change' => '6%',  'dir' => 'up',   'color' => 'red',   'icon' => 'x',        'card' => 'bg-[#faf3ea] border-[#f0e4d2]', 'iconBg' => 'bg-[#b8923e]'],
        //     ['label' => 'Pending Applications', 'value' => 283,  'change' => '10%', 'dir' => 'down', 'color' => 'green', 'icon' => 'clock',    'card' => 'bg-[#e9f5ee] border-[#d3eadb]', 'iconBg' => 'bg-[#0a5c2e]'],
        // ];

        // // ---- Applications by Academic Session (bar chart) ----
        // $sessions = [
        //     'labels'       => ['2022/2023', '2023/2024', '2024/2025', '2025/2026'],
        //     'applications' => [320, 385, 421, 361],
        //     'admitted'     => [210, 265, 298, 256],
        // ];

        // // ---- Applications by Faculty (doughnut) ----
        // $faculties = [
        //     ['name' => 'Faculty of Computing',            'count' => 416, 'color' => '#0a5c2e'],
        //     ['name' => 'Faculty of Engineering',          'count' => 267, 'color' => '#b8923e'],
        //     ['name' => 'Faculty of Science',              'count' => 238, 'color' => '#4fa86f'],
        //     ['name' => 'Faculty of Management Sciences',  'count' => 208, 'color' => '#e3d3b5'],
        //     ['name' => 'Faculty of Social Sciences',      'count' => 178, 'color' => '#2b2f2e'],
        // ];
        // $totalApplications = collect($faculties)->sum('count'); // 1,307 here: use your real total

        // // ---- Gender distribution ----
        // $male   = 580;
        // $female = 420;
        // $genderTotal = $male + $female;

        // // ---- Most applied-for programmes ----
        // $programmes = [
        //     ['name' => 'B.Sc. Computer Science',         'count' => 242, 'percent' => 16],
        //     ['name' => 'B.Sc. Software Engineering',     'count' => 198, 'percent' => 13],
        //     ['name' => 'B.Eng. Civil Engineering',       'count' => 156, 'percent' => 10],
        //     ['name' => 'B.Sc. Accounting',               'count' => 142, 'percent' => 9],
        //     ['name' => 'B.Sc. Business Administration',  'count' => 118, 'percent' => 8],
        // ];
        // $maxProgramme = collect($programmes)->max('count');

        // // ---- Programme capacity ----
        // $capacity  = 4850;
        // $admitted  = 3781;
        // $remaining = $capacity - $admitted;
        // $utilisation = round(($admitted / $capacity) * 100);

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
</section>