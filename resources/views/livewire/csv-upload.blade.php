{{--
    FILE: resources/views/livewire/csv-upload.blade.php
    Step 1: an animated upload screen. Step 2: after a .csv file is uploaded, KPI cards + charts + data preview.
    The sidebar and top bar come from the layout. Livewire needs ONE root element: <main>.
--}}
<main class="space-y-5 p-4 sm:p-6">

    {{-- =====================================================================
         0. ANIMATIONS (small CSS, no config needed)
    ====================================================================== --}}
    <style>
        @keyframes csv-bar   { 0%, 100% { transform: scaleY(.3); } 50% { transform: scaleY(1); } }
        @keyframes csv-float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-8px); } }
        @keyframes csv-rise  { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes csv-shine { from { background-position: 200% 0; } to { background-position: -200% 0; } }

        .csv-bar   { transform-origin: bottom; animation: csv-bar 1.8s ease-in-out infinite; }
        .csv-float { animation: csv-float 3.4s ease-in-out infinite; }
        .csv-rise  { opacity: 0; animation: csv-rise .7s ease-out forwards; }
        .csv-shine { background: linear-gradient(90deg, #0a5c2e 0%, #4fa86f 25%, #b8923e 50%, #4fa86f 75%, #0a5c2e 100%); background-size: 200% 100%; animation: csv-shine 3s linear infinite; }

        @media (prefers-reduced-motion: reduce) {
            .csv-bar, .csv-float, .csv-rise, .csv-shine { animation: none; opacity: 1; }
        }
    </style>

    {{-- =====================================================================
         1. ICONS + SMALL HELPERS
    ====================================================================== --}}
    @php
        $icons = [
            'right'    => 'M8.25 4.5l7.5 7.5-7.5 7.5',
            'upload'   => 'M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5',
            'download' => 'M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3',
            'document' => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z',
            'users'    => 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z',
            'check'    => 'M4.5 12.75l6 6 9-13.5',
            'clock'    => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z',
            'x'        => 'M6 18L18 6M6 6l12 12',
            'chart'    => 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z',
            'spark'    => 'M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z',
            'warning'  => 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z',
        ];

        // KPI cards (only built when a report exists)
        $kpiCards = [];
        if ($report) {
            $k = $report['kpis'];
            $kpiCards[] = ['label' => 'Total Records', 'value' => number_format($k['total']), 'icon' => 'users', 'card' => 'bg-[#e9f5ee] border-[#d3eadb]', 'iconBg' => 'bg-[#0a5c2e]'];
            if ($k['hasStatus']) {
                $kpiCards[] = ['label' => 'Admitted', 'value' => number_format($k['admitted']), 'icon' => 'check', 'card' => 'bg-[#e9f5ee] border-[#d3eadb]', 'iconBg' => 'bg-[#0a5c2e]'];
                $kpiCards[] = ['label' => 'Pending',  'value' => number_format($k['pending']),  'icon' => 'clock', 'card' => 'bg-[#faf3ea] border-[#f0e4d2]', 'iconBg' => 'bg-[#b8923e]'];
                $kpiCards[] = ['label' => 'Rejected', 'value' => number_format($k['rejected']), 'icon' => 'x',     'card' => 'bg-[#faf3ea] border-[#f0e4d2]', 'iconBg' => 'bg-[#b8923e]'];
                $kpiCards[] = ['label' => 'Admission Rate', 'value' => $k['admitRate'] . '%', 'icon' => 'chart', 'card' => 'bg-[#e9f5ee] border-[#d3eadb]', 'iconBg' => 'bg-[#0a5c2e]'];
            }
            if ($k['avgAggregate'] !== null) {
                $kpiCards[] = ['label' => 'Avg. Aggregate', 'value' => $k['avgAggregate'], 'icon' => 'spark', 'card' => 'bg-[#faf3ea] border-[#f0e4d2]', 'iconBg' => 'bg-[#b8923e]'];
            }
        }
    @endphp


    {{-- =====================================================================
         2. PAGE HEADER (breadcrumb, title, buttons)
    ====================================================================== --}}
    <section class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <nav class="mb-1 flex items-center gap-1.5 text-xs text-slate-500">
                <a href="{{ url('/dashboard') }}" class="hover:text-[#0a5c2e]">Dashboard</a>
                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['right'] }}" /></svg>
                <span class="font-medium text-slate-700">Admission Trends</span>
            </nav>
            <h1 class="text-2xl font-bold text-slate-900">Admission Trends</h1>
            <p class="text-sm text-slate-500">Upload a CSV file and turn your admission records into charts.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <button type="button" wire:click="downloadSample"
                    class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['download'] }}" /></svg>
                Download sample CSV
            </button>

            @if ($report)
                <button type="button" wire:click="clearReport"
                        class="inline-flex items-center gap-2 rounded-lg bg-[#0a5c2e] px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-[#0f7a3d]">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['upload'] }}" /></svg>
                    Upload another file
                </button>
            @endif
        </div>
    </section>


    {{-- =====================================================================
         3. UPLOAD SCREEN (shown until a file has been read)
    ====================================================================== --}}
    @if (! $report)
        <section x-data="{ dragging: false, uploading: false, progress: 0 }"
                 x-on:livewire-upload-start="uploading = true; progress = 0"
                 x-on:livewire-upload-progress="progress = $event.detail.progress"
                 x-on:livewire-upload-finish="uploading = false"
                 x-on:livewire-upload-error="uploading = false"
                 class="space-y-6">

            {{-- 3a. Error message (wrong file type, empty file, ...) --}}
            @error('file')
                <div class="flex items-start gap-3 rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['warning'] }}" /></svg>
                    <span>{{ $message }}</span>
                </div>
            @enderror

            {{-- 3b. Drop zone (click to browse, or drag a file onto it) --}}
            <div @click="$refs.file.click()"
                 @dragover.prevent="dragging = true"
                 @dragleave.prevent="dragging = false"
                 @drop.prevent="dragging = false; $refs.file.files = $event.dataTransfer.files; $refs.file.dispatchEvent(new Event('change', { bubbles: true }))"
                 :class="dragging ? 'border-[#0a5c2e] bg-[#e9f5ee] scale-[1.01]' : 'border-slate-300 bg-white hover:border-[#0a5c2e]/60 hover:bg-[#f6fbf8]'"
                 class="relative cursor-pointer overflow-hidden rounded-2xl border-2 border-dashed px-6 py-12 text-center transition duration-300 sm:py-16">

                {{-- Soft background circles --}}
                <div class="pointer-events-none absolute -left-16 -top-16 h-56 w-56 rounded-full bg-[#0a5c2e]/5"></div>
                <div class="pointer-events-none absolute -bottom-20 -right-12 h-64 w-64 rounded-full bg-[#b8923e]/10"></div>

                <input type="file" x-ref="file" wire:model="file" accept=".csv,.txt,text/csv" class="hidden">

                <div class="relative">
                    {{-- Animated illustration: blooming rings + growing bars + floating badges --}}
                    <div class="relative mx-auto h-36 w-36">
                        <span class="absolute inset-0 animate-ping rounded-full bg-[#0a5c2e]/20 motion-reduce:animate-none" style="animation-duration: 2.6s"></span>
                        <span class="absolute inset-0 animate-ping rounded-full bg-[#b8923e]/20 motion-reduce:animate-none" style="animation-duration: 2.6s; animation-delay: 1.3s"></span>

                        <div class="csv-float relative flex h-36 w-36 items-center justify-center rounded-full bg-white shadow-xl ring-1 ring-slate-200">
                            <div class="flex h-16 items-end gap-2">
                                <span class="csv-bar block h-10 w-3 rounded-t bg-[#0a5c2e]" style="animation-delay: 0s"></span>
                                <span class="csv-bar block h-14 w-3 rounded-t bg-[#4fa86f]" style="animation-delay: .25s"></span>
                                <span class="csv-bar block h-16 w-3 rounded-t bg-[#b8923e]" style="animation-delay: .5s"></span>
                                <span class="csv-bar block h-12 w-3 rounded-t bg-[#0a5c2e]" style="animation-delay: .75s"></span>
                                <span class="csv-bar block h-8 w-3 rounded-t bg-[#c9a867]" style="animation-delay: 1s"></span>
                            </div>
                        </div>

                        {{-- floating badge: upload arrow --}}
                        <span class="csv-float absolute -right-3 -top-1 flex h-11 w-11 items-center justify-center rounded-full bg-[#0a5c2e] text-white shadow-lg" style="animation-delay: .8s">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['upload'] }}" /></svg>
                        </span>
                        {{-- floating badge: file --}}
                        <span class="csv-float absolute -bottom-1 -left-4 flex h-10 w-10 items-center justify-center rounded-full bg-[#b8923e] text-white shadow-lg" style="animation-delay: 1.6s">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['document'] }}" /></svg>
                        </span>
                    </div>

                    <h2 class="mt-8 text-2xl font-bold text-slate-900 sm:text-3xl">Upload a <span class="text-[#0a5c2e]">.csv</span> file to analyse admission trends</h2>
                    <p class="mx-auto mt-3 max-w-xl text-sm text-slate-500">
                        Drag and drop your file here, or click to browse. We read it instantly and turn it into bar charts, pie charts and trend lines.
                    </p>

                    <span class="mt-6 inline-flex items-center gap-2 rounded-lg bg-[#0a5c2e] px-6 py-3 text-sm font-semibold text-white shadow-md transition hover:bg-[#0f7a3d]">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['upload'] }}" /></svg>
                        Choose CSV file
                    </span>

                    <p class="mt-4 text-xs text-slate-400">CSV only &middot; up to 5 MB &middot; your file is deleted right after it is read</p>
                </div>

                {{-- Upload progress bar (while the file is travelling to the server) --}}
                <div x-show="uploading" x-cloak class="absolute inset-x-0 bottom-0">
                    <div class="h-1.5 bg-slate-100"><div class="csv-shine h-full transition-all duration-200" :style="`width: ${progress}%`"></div></div>
                </div>

                {{-- "Reading your file" overlay (upload + analysis) --}}
                <div wire:loading.flex wire:target="file" class="absolute inset-0 z-10 flex-col items-center justify-center gap-3 bg-white/90 backdrop-blur-sm">
                    <svg class="h-10 w-10 animate-spin text-[#0a5c2e]" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" class="opacity-25"/><path fill="currentColor" class="opacity-75" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
                    <p class="text-sm font-semibold text-slate-700">Reading your file and building the charts...</p>
                    <p class="text-xs text-slate-500" x-show="uploading" x-text="`Uploading ${progress}%`"></p>
                </div>
            </div>

            {{-- 3c. How it works (cards slide in one after the other) --}}
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                @foreach ([
                    ['1', 'Upload your file', 'Drop a .csv exported from Excel, Google Sheets or your database.'],
                    ['2', 'We read it for you', 'Columns such as status, session, faculty, programme and scores are detected automatically.'],
                    ['3', 'See the trends', 'Get summary numbers, bar charts, pie charts and a monthly trend line in seconds.'],
                ] as $i => [$num, $title, $text])
                    <div class="csv-rise rounded-xl border border-slate-200 bg-white p-5" style="animation-delay: {{ 0.15 + $i * 0.18 }}s">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-[#e9f5ee] text-sm font-bold text-[#0a5c2e]">{{ $num }}</span>
                        <p class="mt-3 font-semibold text-slate-900">{{ $title }}</p>
                        <p class="mt-1 text-sm text-slate-500">{{ $text }}</p>
                    </div>
                @endforeach
            </div>

            {{-- 3d. Columns that the page understands --}}
            <div class="csv-rise rounded-xl border border-slate-200 bg-white p-5" style="animation-delay: .8s">
                <p class="text-sm font-semibold text-slate-900">Columns your file can contain</p>
                <p class="mt-1 text-xs text-slate-500">Use any of these headers. Columns you do not have are simply skipped. Not sure? Download the sample file (300 student records) and look at its format.</p>
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach (['status', 'academic_session', 'faculty', 'programme', 'gender', 'state_of_origin', 'application_date', 'utme_score', 'post_utme_score', 'aggregate_score'] as $col)
                        <span class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 font-mono text-xs text-slate-600">{{ $col }}</span>
                    @endforeach
                </div>
            </div>
        </section>


    {{-- =====================================================================
         4. RESULTS (after the file has been read)
    ====================================================================== --}}
    @else
        <div wire:key="report-{{ $reportId }}" x-data="csvReport(@js($report))" class="space-y-5">

            {{-- 4a. File information --}}
            <section class="csv-rise flex flex-col gap-3 rounded-xl border border-slate-200 bg-white p-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-lg bg-[#e9f5ee] text-[#0a5c2e]">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['document'] }}" /></svg>
                    </span>
                    <div>
                        <p class="font-semibold text-slate-900">{{ $report['fileName'] }}</p>
                        <p class="text-xs text-slate-500">{{ number_format($report['rows']) }} records &middot; {{ count($report['columns']) }} columns</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-1.5">
                    @foreach (array_slice($report['columns'], 0, 8) as $col)
                        <span class="rounded-full bg-slate-100 px-2.5 py-0.5 font-mono text-[11px] text-slate-600">{{ $col }}</span>
                    @endforeach
                    @if (count($report['columns']) > 8)
                        <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-[11px] text-slate-500">+{{ count($report['columns']) - 8 }} more</span>
                    @endif
                </div>
            </section>

            @if (! empty($report['missing']))
                <div class="flex items-start gap-3 rounded-lg border border-yellow-300 bg-yellow-50 px-4 py-3 text-xs text-yellow-800">
                    <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['warning'] }}" /></svg>
                    <p>These columns were not found, so the matching charts are skipped: <span class="font-mono font-semibold">{{ implode(', ', $report['missing']) }}</span></p>
                </div>
            @endif

            {{-- 4b. KPI cards --}}
            <section class="grid grid-cols-2 gap-4 lg:grid-cols-3 xl:grid-cols-6">
                @foreach ($kpiCards as $i => $c)
                    <div class="csv-rise rounded-xl border p-4 {{ $c['card'] }}" style="animation-delay: {{ $i * 0.08 }}s">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-white {{ $c['iconBg'] }}">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[$c['icon']] }}" /></svg>
                            </span>
                            <div class="min-w-0">
                                <p class="truncate text-xs font-medium text-slate-700">{{ $c['label'] }}</p>
                                <p class="truncate text-xl font-bold text-slate-900">{{ $c['value'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </section>

            {{-- 4c. Key insights (written automatically from the numbers) --}}
            @if (! empty($report['insights']))
                <section class="csv-rise rounded-xl border border-slate-200 bg-white p-5" style="animation-delay: .2s">
                    <h2 class="flex items-center gap-2 font-semibold text-slate-900">
                        <svg class="h-5 w-5 text-[#b8923e]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['spark'] }}" /></svg>
                        Key insights
                    </h2>
                    <ul class="mt-3 grid grid-cols-1 gap-x-8 gap-y-2 text-sm text-slate-700 md:grid-cols-2">
                        @foreach ($report['insights'] as $line)
                            <li class="flex gap-2"><span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-[#0a5c2e]"></span>{{ $line }}</li>
                        @endforeach
                    </ul>
                </section>
            @endif

            {{-- 4d. CHARTS (each card only shows when your file has that data) --}}
            <section class="grid grid-cols-1 gap-5 xl:grid-cols-6">

                @if ($report['bySession'])
                    <div class="csv-rise rounded-xl border border-slate-200 bg-white p-5 xl:col-span-4" style="animation-delay: .1s">
                        <h2 class="font-semibold text-slate-900">Applications by Academic Session</h2>
                        <p class="text-xs text-slate-500">Bar chart: applications received vs admitted</p>
                        <div class="mt-4 h-72" wire:ignore><canvas x-ref="session"></canvas></div>
                    </div>
                @endif

                @if ($report['byStatus'])
                    <div class="csv-rise rounded-xl border border-slate-200 bg-white p-5 xl:col-span-2" style="animation-delay: .2s">
                        <h2 class="font-semibold text-slate-900">Application Status</h2>
                        <p class="text-xs text-slate-500">Doughnut chart</p>
                        <div class="mt-4 h-72" wire:ignore><canvas x-ref="status"></canvas></div>
                    </div>
                @endif

                @if ($report['byFaculty'])
                    <div class="csv-rise rounded-xl border border-slate-200 bg-white p-5 xl:col-span-3" style="animation-delay: .3s">
                        <h2 class="font-semibold text-slate-900">Applications by Faculty</h2>
                        <p class="text-xs text-slate-500">Pie chart</p>
                        <div class="mt-4 h-72" wire:ignore><canvas x-ref="faculty"></canvas></div>
                    </div>
                @endif

                @if ($report['byProgramme'])
                    <div class="csv-rise rounded-xl border border-slate-200 bg-white p-5 xl:col-span-3" style="animation-delay: .4s">
                        <h2 class="font-semibold text-slate-900">Most Applied-for Programmes</h2>
                        <p class="text-xs text-slate-500">Top 8, horizontal bar chart</p>
                        <div class="mt-4 h-72" wire:ignore><canvas x-ref="programme"></canvas></div>
                    </div>
                @endif

                @if ($report['byMonth'])
                    <div class="csv-rise rounded-xl border border-slate-200 bg-white p-5 xl:col-span-6" style="animation-delay: .5s">
                        <h2 class="font-semibold text-slate-900">Monthly Application Trend</h2>
                        <p class="text-xs text-slate-500">Line chart: applications received each month</p>
                        <div class="mt-4 h-72" wire:ignore><canvas x-ref="month"></canvas></div>
                    </div>
                @endif

                @if ($report['byGender'])
                    <div class="csv-rise rounded-xl border border-slate-200 bg-white p-5 xl:col-span-2" style="animation-delay: .6s">
                        <h2 class="font-semibold text-slate-900">Gender Distribution</h2>
                        <p class="text-xs text-slate-500">Pie chart</p>
                        <div class="mt-4 h-64" wire:ignore><canvas x-ref="gender"></canvas></div>
                    </div>
                @endif

                @if ($report['scoreBuckets'])
                    <div class="csv-rise rounded-xl border border-slate-200 bg-white p-5 xl:col-span-2" style="animation-delay: .7s">
                        <h2 class="font-semibold text-slate-900">Aggregate Score Distribution</h2>
                        <p class="text-xs text-slate-500">How many applicants fall in each score range</p>
                        <div class="mt-4 h-64" wire:ignore><canvas x-ref="scores"></canvas></div>
                    </div>
                @endif

                @if ($report['byState'])
                    <div class="csv-rise rounded-xl border border-slate-200 bg-white p-5 xl:col-span-2" style="animation-delay: .8s">
                        <h2 class="font-semibold text-slate-900">Top States of Origin</h2>
                        <p class="text-xs text-slate-500">Top 8, bar chart</p>
                        <div class="mt-4 h-64" wire:ignore><canvas x-ref="state"></canvas></div>
                    </div>
                @endif
            </section>

            {{-- 4e. Preview of the first rows of the file --}}
            <section class="csv-rise overflow-hidden rounded-xl border border-slate-200 bg-white" style="animation-delay: .3s">
                <div class="border-b border-slate-200 px-5 py-4">
                    <h2 class="font-semibold text-slate-900">Data preview</h2>
                    <p class="text-xs text-slate-500">The first {{ count($report['preview']['rows']) }} rows of your file</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr>
                                @foreach ($report['preview']['headers'] as $h)
                                    <th class="whitespace-nowrap px-4 py-2.5 font-semibold">{{ $h }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($report['preview']['rows'] as $row)
                                <tr class="hover:bg-slate-50">
                                    @foreach ($row as $cell)
                                        <td class="whitespace-nowrap px-4 py-2.5 text-slate-700">{{ $cell }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    @endif


    {{-- =====================================================================
         5. CHART SCRIPT (Chart.js is loaded in the layout). Draws every chart from the report numbers.
    ====================================================================== --}}
    @script
    <script>
        window.csvReport = (r) => ({
            charts: [],

            init() {
                const C = { green: '#0a5c2e', light: '#4fa86f', gold: '#b8923e', tan: '#c9a867', dark: '#2b2f2e', red: '#c0392b', grey: '#94a3b8' };
                const palette = [C.green, C.gold, C.light, C.tan, C.dark, '#7c9885', '#d4a24c', '#a3c9b0'];
                const statusColor = { admitted: C.green, pending: C.gold, rejected: C.red, other: C.grey };
                const base = { responsive: true, maintainAspectRatio: false };
                const legendBottom = { legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } } };
                const noLegend = { legend: { display: false } };
                const grid = { color: '#eef0f2' };

                const make = (ref, config) => {
                    if (this.$refs[ref]) this.charts.push(new Chart(this.$refs[ref], config));
                };

                // Bar: applications vs admitted per session
                if (r.bySession) make('session', {
                    type: 'bar',
                    data: { labels: r.bySession.labels, datasets: [
                        { label: 'Applications', data: r.bySession.applications, backgroundColor: C.green, borderRadius: 4 },
                        { label: 'Admitted', data: r.bySession.admitted, backgroundColor: C.tan, borderRadius: 4 },
                    ] },
                    options: { ...base, plugins: legendBottom, scales: { y: { beginAtZero: true, grid }, x: { grid: { display: false } } } },
                });

                // Doughnut: status
                if (r.byStatus) make('status', {
                    type: 'doughnut',
                    data: { labels: r.byStatus.labels.map(l => l.charAt(0).toUpperCase() + l.slice(1)),
                            datasets: [{ data: r.byStatus.values, backgroundColor: r.byStatus.labels.map(l => statusColor[l] || C.grey), borderWidth: 2, borderColor: '#fff' }] },
                    options: { ...base, cutout: '62%', plugins: legendBottom },
                });

                // Pie: faculty
                if (r.byFaculty) make('faculty', {
                    type: 'pie',
                    data: { labels: r.byFaculty.labels, datasets: [{ data: r.byFaculty.values, backgroundColor: palette, borderWidth: 2, borderColor: '#fff' }] },
                    options: { ...base, plugins: { legend: { position: 'right', labels: { usePointStyle: true, boxWidth: 8 } } } },
                });

                // Horizontal bar: programmes
                if (r.byProgramme) make('programme', {
                    type: 'bar',
                    data: { labels: r.byProgramme.labels, datasets: [{ data: r.byProgramme.values, backgroundColor: C.green, borderRadius: 4 }] },
                    options: { ...base, indexAxis: 'y', plugins: noLegend, scales: { x: { beginAtZero: true, grid }, y: { grid: { display: false } } } },
                });

                // Line: monthly trend
                if (r.byMonth) make('month', {
                    type: 'line',
                    data: { labels: r.byMonth.labels, datasets: [{ label: 'Applications', data: r.byMonth.values, borderColor: C.green, backgroundColor: 'rgba(10,92,46,.12)', fill: true, tension: .35, pointRadius: 2, pointBackgroundColor: C.green }] },
                    options: { ...base, plugins: noLegend, scales: { y: { beginAtZero: true, grid }, x: { grid: { display: false }, ticks: { maxTicksLimit: 12 } } } },
                });

                // Pie: gender
                if (r.byGender) make('gender', {
                    type: 'pie',
                    data: { labels: r.byGender.labels, datasets: [{ data: r.byGender.values, backgroundColor: [C.green, C.tan, C.grey], borderWidth: 2, borderColor: '#fff' }] },
                    options: { ...base, plugins: legendBottom },
                });

                // Bar: aggregate score ranges
                if (r.scoreBuckets) make('scores', {
                    type: 'bar',
                    data: { labels: r.scoreBuckets.labels, datasets: [{ data: r.scoreBuckets.values, backgroundColor: C.gold, borderRadius: 4, categoryPercentage: .95, barPercentage: .95 }] },
                    options: { ...base, plugins: noLegend, scales: { y: { beginAtZero: true, grid }, x: { grid: { display: false } } } },
                });

                // Bar: states
                if (r.byState) make('state', {
                    type: 'bar',
                    data: { labels: r.byState.labels, datasets: [{ data: r.byState.values, backgroundColor: C.light, borderRadius: 4 }] },
                    options: { ...base, plugins: noLegend, scales: { y: { beginAtZero: true, grid }, x: { grid: { display: false } } } },
                });
            },

            // called by Alpine when the results are removed ("Upload another file")
            destroy() {
                this.charts.forEach(c => c.destroy());
            },
        });
    </script>
    @endscript

</main>
