{{--
    FILE: resources/views/livewire/programmes.blade.php
    One page to manage Faculties, Departments and Programmes (list + add + delete).
    The sidebar and top bar come from the layout (app.blade.php). Livewire needs ONE root element: <main>.
--}}
<main class="space-y-5 p-4 sm:p-6">

    {{-- =====================================================================
         0. ICONS + SMALL HELPERS
    ====================================================================== --}}
    @php
        $icons = [
            'search'  => 'M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z',
            'plus'    => 'M12 4.5v15m7.5-7.5h-15',
            'trash'   => 'M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0',
            'x'       => 'M6 18L18 6M6 6l12 12',
            'right'   => 'M8.25 4.5l7.5 7.5-7.5 7.5',
            'left'    => 'M15.75 19.5L8.25 12l7.5-7.5',
            'chevron' => 'M19.5 8.25l-7.5 7.5-7.5-7.5',
            'check'   => 'M4.5 12.75l6 6 9-13.5',
            'cap'     => 'M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5',
            'library' => 'M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z',
            'office'  => 'M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z',
            'warning' => 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z',
        ];

        // Cards at the top
        $cards = [
            ['label' => 'Faculties',            'value' => $stats['faculties'],   'icon' => 'office',  'card' => 'bg-[#e9f5ee] border-[#d3eadb]', 'iconBg' => 'bg-[#0a5c2e]'],
            ['label' => 'Departments',          'value' => $stats['departments'], 'icon' => 'library', 'card' => 'bg-[#faf3ea] border-[#f0e4d2]', 'iconBg' => 'bg-[#b8923e]'],
            ['label' => 'Programmes',           'value' => $stats['programmes'],  'icon' => 'cap',     'card' => 'bg-[#e9f5ee] border-[#d3eadb]', 'iconBg' => 'bg-[#0a5c2e]'],
            ['label' => 'Active Programmes',    'value' => $stats['active'],      'icon' => 'check',   'card' => 'bg-[#faf3ea] border-[#f0e4d2]', 'iconBg' => 'bg-[#b8923e]'],
        ];

        // Tabs
        $tabs = [
            'programmes'  => ['label' => 'Programmes',  'count' => $stats['programmes']],
            'departments' => ['label' => 'Departments', 'count' => $stats['departments']],
            'faculties'   => ['label' => 'Faculties',   'count' => $stats['faculties']],
        ];

        // Same look for every input / select in this page
        $input = 'w-full rounded-lg border border-slate-300 bg-white py-2 px-3 text-sm text-slate-800 placeholder-slate-400 focus:border-[#0a5c2e] focus:outline-none focus:ring-2 focus:ring-[#0a5c2e]/20';
        $select = 'w-full appearance-none rounded-lg border border-slate-300 bg-white py-2 pl-3 pr-9 text-sm text-slate-800 focus:border-[#0a5c2e] focus:outline-none focus:ring-2 focus:ring-[#0a5c2e]/20';

        $hasFilters = $search !== '' || $facultyFilter !== '' || $statusFilter !== '';
    @endphp


    {{-- =====================================================================
         1. SUCCESS / ERROR MESSAGE (disappears after 4 seconds)
    ====================================================================== --}}
    @if (session('success') || session('error'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
             class="flex items-center justify-between rounded-lg border px-4 py-3 text-sm
                    {{ session('success') ? 'border-green-300 bg-green-50 text-green-800' : 'border-red-300 bg-red-50 text-red-700' }}">
            <span>{{ session('success') ?? session('error') }}</span>
            <button type="button" @click="show = false" class="ml-4 opacity-60 hover:opacity-100">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['x'] }}" /></svg>
            </button>
        </div>
    @endif


    {{-- =====================================================================
         2. PAGE HEADER (breadcrumb, title, the 3 "Add" buttons)
    ====================================================================== --}}
    <section class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <nav class="mb-1 flex items-center gap-1.5 text-xs text-slate-500">
                <a href="{{ url('/dashboard') }}" class="hover:text-[#0a5c2e]">Dashboard</a>
                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['right'] }}" /></svg>
                <span class="font-medium text-slate-700">Programmes</span>
            </nav>
            <h1 class="text-2xl font-bold text-slate-900">Programmes</h1>
            <p class="text-sm text-slate-500">Manage faculties, departments and programmes in one place.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <button type="button" wire:click="openFacultyModal"
                    class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['plus'] }}" /></svg>
                Add Faculty
            </button>
            <button type="button" wire:click="openDepartmentModal"
                    class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['plus'] }}" /></svg>
                Add Department
            </button>
            <button type="button" wire:click="openProgrammeModal"
                    class="inline-flex items-center gap-2 rounded-lg bg-[#0a5c2e] px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-[#0f7a3d]">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['plus'] }}" /></svg>
                Add Programme
            </button>
        </div>
    </section>


    {{-- =====================================================================
         3. SUMMARY CARDS
    ====================================================================== --}}
    <section class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        @foreach ($cards as $c)
            <div class="rounded-xl border p-4 {{ $c['card'] }}">
                <div class="flex items-center gap-3">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl text-white {{ $c['iconBg'] }}">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[$c['icon']] }}" /></svg>
                    </span>
                    <div>
                        <p class="text-xs font-medium text-slate-700">{{ $c['label'] }}</p>
                        <p class="text-2xl font-bold text-slate-900">{{ number_format($c['value']) }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </section>


    {{-- =====================================================================
         4. TABS (Programmes | Departments | Faculties)
    ====================================================================== --}}
    <section class="flex gap-1 overflow-x-auto border-b border-slate-200">
        @foreach ($tabs as $key => $t)
            <button type="button" wire:click="setTab('{{ $key }}')"
                    class="whitespace-nowrap border-b-2 px-4 py-2.5 text-sm font-medium transition
                           {{ $tab === $key ? 'border-[#0a5c2e] text-[#0a5c2e]' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                {{ $t['label'] }}
                <span class="ml-1 rounded-full bg-slate-100 px-2 py-0.5 text-xs text-slate-600">{{ $t['count'] }}</span>
            </button>
        @endforeach
    </section>


    {{-- =====================================================================
         5. FILTER BAR (search for every tab, faculty filter, status filter)
    ====================================================================== --}}
    <section class="rounded-xl border border-slate-200 bg-white p-4">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-12 xl:items-end">

            {{-- 5a. Search --}}
            <div class="xl:col-span-5">
                <label for="search" class="mb-1 block text-xs font-medium text-slate-600">Search</label>
                <div class="relative">
                    <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['search'] }}" /></svg>
                    <input id="search" type="text" wire:model.live.debounce.400ms="search"
                           placeholder="{{ $tab === 'programmes' ? 'Programme, department or faculty' : ($tab === 'departments' ? 'Department, code or faculty' : 'Faculty name or code') }}"
                           class="{{ $input }} pl-9">
                </div>
            </div>

            {{-- 5b. Faculty filter (programmes + departments tabs) --}}
            @if ($tab !== 'faculties')
                <div class="xl:col-span-3">
                    <label for="facultyFilter" class="mb-1 block text-xs font-medium text-slate-600">Faculty</label>
                    <div class="relative">
                        <select id="facultyFilter" wire:model.live="facultyFilter" class="{{ $select }}">
                            <option value="">All Faculties</option>
                            @foreach ($facultyOptions as $f)
                                <option value="{{ $f->id }}">{{ $f->name }}</option>
                            @endforeach
                        </select>
                        <svg class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['chevron'] }}" /></svg>
                    </div>
                </div>
            @endif

            {{-- 5c. Status filter (programmes tab only) --}}
            @if ($tab === 'programmes')
                <div class="xl:col-span-2">
                    <label for="statusFilter" class="mb-1 block text-xs font-medium text-slate-600">Status</label>
                    <div class="relative">
                        <select id="statusFilter" wire:model.live="statusFilter" class="{{ $select }}">
                            <option value="">All</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        <svg class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['chevron'] }}" /></svg>
                    </div>
                </div>
            @endif

            {{-- 5d. Clear --}}
            @if ($hasFilters)
                <div class="xl:col-span-2">
                    <button type="button" wire:click="setTab('{{ $tab }}')"
                            class="inline-flex items-center gap-1 whitespace-nowrap rounded-lg border border-slate-300 px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['x'] }}" /></svg>
                        Clear
                    </button>
                </div>
            @endif
        </div>
    </section>


    {{-- =====================================================================
         6. TABLES (only the table of the selected tab is shown)
    ====================================================================== --}}
    <section class="overflow-hidden rounded-xl border border-slate-200 bg-white" wire:loading.class="opacity-60">

        {{-- ---------------------------------------------------------------
             6a. PROGRAMMES TABLE  (programme -> department -> faculty)
        ---------------------------------------------------------------- --}}
        @if ($tab === 'programmes')
            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-600">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Programme</th>
                            <th class="px-5 py-3 font-semibold">Department</th>
                            <th class="px-5 py-3 font-semibold">Faculty</th>
                            <th class="px-5 py-3 font-semibold">Duration</th>
                            <th class="px-5 py-3 font-semibold">Applications</th>
                            <th class="px-5 py-3 font-semibold">Status</th>
                            <th class="px-5 py-3 text-right font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($programmes as $programme)
                            <tr wire:key="programme-{{ $programme->id }}" class="hover:bg-slate-50">
                                <td class="px-5 py-3.5">
                                    <p class="font-medium text-slate-900">{{ $programme->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $programme->degree_award_type ?? 'No degree set' }}</p>
                                </td>
                                <td class="px-5 py-3.5 text-slate-700">{{ $programme->department->name }}</td>
                                <td class="px-5 py-3.5 text-slate-700">{{ $programme->department->faculty->name }}</td>
                                <td class="whitespace-nowrap px-5 py-3.5 text-slate-700">{{ $programme->duration_years ? $programme->duration_years . ' years' : '-' }}</td>
                                <td class="px-5 py-3.5 text-slate-700">{{ number_format($programme->applications_count) }}</td>
                                <td class="px-5 py-3.5">
                                    <span class="inline-block rounded-full border px-3 py-0.5 text-xs font-medium
                                                 {{ $programme->is_active ? 'border-green-300 bg-green-100 text-green-800' : 'border-slate-300 bg-slate-100 text-slate-600' }}">
                                        {{ $programme->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-end">
                                        <button type="button" title="Delete" wire:click="confirmDelete('programme', {{ $programme->id }})"
                                                class="rounded-md p-2 text-slate-500 hover:bg-red-50 hover:text-red-600">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['trash'] }}" /></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-16 text-center">
                                    <p class="text-sm font-semibold text-slate-800">No programmes found</p>
                                    <p class="mt-1 text-xs text-slate-500">Try a different search, or add a new programme.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer: result count + pagination --}}
            @php
                $page     = $programmes->currentPage();
                $lastPage = $programmes->lastPage();
                $start    = max(1, $page - 2);
                $end      = min($lastPage, $page + 2);
            @endphp
            <div class="flex flex-col items-center justify-between gap-3 border-t border-slate-200 px-5 py-4 sm:flex-row">
                <p class="text-xs text-slate-600">
                    Showing <span class="font-semibold">{{ $programmes->firstItem() ?? 0 }}</span> to <span class="font-semibold">{{ $programmes->lastItem() ?? 0 }}</span>
                    of <span class="font-semibold">{{ number_format($programmes->total()) }}</span> programmes
                </p>

                @if ($lastPage > 1)
                    <div class="flex items-center gap-1">
                        <button type="button" wire:click="gotoPage({{ $page - 1 }})" @disabled($page <= 1)
                                class="rounded-md border border-slate-300 p-2 text-slate-600 hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['left'] }}" /></svg>
                        </button>

                        @for ($p = $start; $p <= $end; $p++)
                            <button type="button" wire:click="gotoPage({{ $p }})"
                                    class="min-w-[2.25rem] rounded-md border px-2 py-1.5 text-xs font-medium
                                           {{ $p === $page ? 'border-[#0a5c2e] bg-[#0a5c2e] text-white' : 'border-slate-300 text-slate-700 hover:bg-slate-50' }}">{{ $p }}</button>
                        @endfor

                        <button type="button" wire:click="gotoPage({{ $page + 1 }})" @disabled($page >= $lastPage)
                                class="rounded-md border border-slate-300 p-2 text-slate-600 hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['right'] }}" /></svg>
                        </button>
                    </div>
                @endif
            </div>
        @endif


        {{-- ---------------------------------------------------------------
             6b. DEPARTMENTS TABLE  (department -> faculty)
        ---------------------------------------------------------------- --}}
        @if ($tab === 'departments')
            <div class="overflow-x-auto">
                <table class="w-full min-w-[700px] text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-600">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Department</th>
                            <th class="px-5 py-3 font-semibold">Code</th>
                            <th class="px-5 py-3 font-semibold">Faculty</th>
                            <th class="px-5 py-3 font-semibold">Programmes</th>
                            <th class="px-5 py-3 text-right font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($departments as $department)
                            <tr wire:key="department-{{ $department->id }}" class="hover:bg-slate-50">
                                <td class="px-5 py-3.5 font-medium text-slate-900">{{ $department->name }}</td>
                                <td class="px-5 py-3.5 text-slate-700">{{ $department->code ?? '-' }}</td>
                                <td class="px-5 py-3.5 text-slate-700">{{ $department->faculty->name }}</td>
                                <td class="px-5 py-3.5 text-slate-700">{{ $department->programmes_count }}</td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-end">
                                        <button type="button" title="Delete" wire:click="confirmDelete('department', {{ $department->id }})"
                                                class="rounded-md p-2 text-slate-500 hover:bg-red-50 hover:text-red-600">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['trash'] }}" /></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-16 text-center">
                                    <p class="text-sm font-semibold text-slate-800">No departments found</p>
                                    <p class="mt-1 text-xs text-slate-500">Try a different search, or add a new department.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif


        {{-- ---------------------------------------------------------------
             6c. FACULTIES TABLE
        ---------------------------------------------------------------- --}}
        @if ($tab === 'faculties')
            <div class="overflow-x-auto">
                <table class="w-full min-w-[600px] text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-600">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Faculty</th>
                            <th class="px-5 py-3 font-semibold">Code</th>
                            <th class="px-5 py-3 font-semibold">Departments</th>
                            <th class="px-5 py-3 font-semibold">Programmes</th>
                            <th class="px-5 py-3 text-right font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($faculties as $faculty)
                            <tr wire:key="faculty-{{ $faculty->id }}" class="hover:bg-slate-50">
                                <td class="px-5 py-3.5 font-medium text-slate-900">{{ $faculty->name }}</td>
                                <td class="px-5 py-3.5 text-slate-700">{{ $faculty->code ?? '-' }}</td>
                                <td class="px-5 py-3.5 text-slate-700">{{ $faculty->departments_count }}</td>
                                <td class="px-5 py-3.5 text-slate-700">{{ $faculty->programmes_count }}</td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-end">
                                        <button type="button" title="Delete" wire:click="confirmDelete('faculty', {{ $faculty->id }})"
                                                class="rounded-md p-2 text-slate-500 hover:bg-red-50 hover:text-red-600">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['trash'] }}" /></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-16 text-center">
                                    <p class="text-sm font-semibold text-slate-800">No faculties found</p>
                                    <p class="mt-1 text-xs text-slate-500">Try a different search, or add a new faculty.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </section>


    {{-- =====================================================================
         7. MODALS (Add Faculty, Add Department, Add Programme, Confirm Delete)
            Click the dark background or press Esc to close.
    ====================================================================== --}}

    {{-- ---------------------------------------------------------------
         7a. ADD FACULTY
    ---------------------------------------------------------------- --}}
    @if ($showFacultyModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" wire:click.self="closeModals" wire:keydown.escape.window="closeModals">
            <form wire:submit="saveFaculty" class="w-full max-w-md rounded-xl bg-white shadow-xl">
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <h3 class="font-semibold text-slate-900">Add Faculty</h3>
                    <button type="button" wire:click="closeModals" class="text-slate-400 hover:text-slate-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['x'] }}" /></svg>
                    </button>
                </div>

                <div class="space-y-4 px-5 py-5">
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-600">Faculty name</label>
                        <input type="text" wire:model="faculty_name" placeholder="e.g. Faculty of Arts" class="{{ $input }}">
                        @error('faculty_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-600">Faculty code <span class="text-slate-400">(optional)</span></label>
                        <input type="text" wire:model="faculty_code" placeholder="e.g. FAC006" class="{{ $input }}">
                        @error('faculty_code') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t border-slate-200 px-5 py-4">
                    <button type="button" wire:click="closeModals" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</button>
                    <button type="submit" wire:loading.attr="disabled" wire:target="saveFaculty"
                            class="rounded-lg bg-[#0a5c2e] px-4 py-2 text-sm font-medium text-white hover:bg-[#0f7a3d] disabled:opacity-60">Save Faculty</button>
                </div>
            </form>
        </div>
    @endif


    {{-- ---------------------------------------------------------------
         7b. ADD DEPARTMENT (choose the faculty it belongs to)
    ---------------------------------------------------------------- --}}
    @if ($showDepartmentModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" wire:click.self="closeModals" wire:keydown.escape.window="closeModals">
            <form wire:submit="saveDepartment" class="w-full max-w-md rounded-xl bg-white shadow-xl">
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <h3 class="font-semibold text-slate-900">Add Department</h3>
                    <button type="button" wire:click="closeModals" class="text-slate-400 hover:text-slate-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['x'] }}" /></svg>
                    </button>
                </div>

                <div class="space-y-4 px-5 py-5">
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-600">Faculty</label>
                        <div class="relative">
                            <select wire:model="department_faculty_id" class="{{ $select }}">
                                <option value="">Select a faculty</option>
                                @foreach ($facultyOptions as $f)
                                    <option value="{{ $f->id }}">{{ $f->name }}</option>
                                @endforeach
                            </select>
                            <svg class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['chevron'] }}" /></svg>
                        </div>
                        @error('department_faculty_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-600">Department name</label>
                        <input type="text" wire:model="department_name" placeholder="e.g. Computer Engineering" class="{{ $input }}">
                        @error('department_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-600">Department code <span class="text-slate-400">(optional)</span></label>
                        <input type="text" wire:model="department_code" placeholder="e.g. DEP016" class="{{ $input }}">
                        @error('department_code') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t border-slate-200 px-5 py-4">
                    <button type="button" wire:click="closeModals" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</button>
                    <button type="submit" wire:loading.attr="disabled" wire:target="saveDepartment"
                            class="rounded-lg bg-[#0a5c2e] px-4 py-2 text-sm font-medium text-white hover:bg-[#0f7a3d] disabled:opacity-60">Save Department</button>
                </div>
            </form>
        </div>
    @endif


    {{-- ---------------------------------------------------------------
         7c. ADD PROGRAMME (faculty first, then the departments of that faculty)
    ---------------------------------------------------------------- --}}
    @if ($showProgrammeModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-black/50 p-4" wire:click.self="closeModals" wire:keydown.escape.window="closeModals">
            <form wire:submit="saveProgramme" class="w-full max-w-lg rounded-xl bg-white shadow-xl">
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <h3 class="font-semibold text-slate-900">Add Programme</h3>
                    <button type="button" wire:click="closeModals" class="text-slate-400 hover:text-slate-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['x'] }}" /></svg>
                    </button>
                </div>

                <div class="space-y-4 px-5 py-5">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        {{-- Faculty (live, so the department list below updates) --}}
                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-600">Faculty</label>
                            <div class="relative">
                                <select wire:model.live="programme_faculty_id" class="{{ $select }}">
                                    <option value="">Select a faculty</option>
                                    @foreach ($facultyOptions as $f)
                                        <option value="{{ $f->id }}">{{ $f->name }}</option>
                                    @endforeach
                                </select>
                                <svg class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['chevron'] }}" /></svg>
                            </div>
                            @error('programme_faculty_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Department (only the departments of the selected faculty) --}}
                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-600">Department</label>
                            <div class="relative">
                                <select wire:model="programme_department_id" @disabled($programme_faculty_id === '') class="{{ $select }} disabled:bg-slate-50 disabled:text-slate-400">
                                    <option value="">{{ $programme_faculty_id === '' ? 'Choose a faculty first' : 'Select a department' }}</option>
                                    @foreach ($departmentOptions as $d)
                                        <option value="{{ $d->id }}">{{ $d->name }}</option>
                                    @endforeach
                                </select>
                                <svg class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['chevron'] }}" /></svg>
                            </div>
                            @error('programme_department_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-600">Programme name</label>
                        <input type="text" wire:model="programme_name" placeholder="e.g. B.Sc. Computer Science" class="{{ $input }}">
                        @error('programme_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-600">Degree <span class="text-slate-400">(optional)</span></label>
                            <input type="text" list="degree-list" wire:model="degree_award_type" placeholder="e.g. B.Sc." class="{{ $input }}">
                            <datalist id="degree-list">
                                <option value="B.Sc."></option>
                                <option value="B.Eng."></option>
                                <option value="B.Tech."></option>
                                <option value="B.A."></option>
                                <option value="B.Ed."></option>
                                <option value="B.Agric."></option>
                            </datalist>
                            @error('degree_award_type') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-600">Duration (years)</label>
                            <input type="number" min="1" max="8" wire:model="duration_years" class="{{ $input }}">
                            @error('duration_years') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <label class="flex items-center gap-2 text-sm text-slate-700">
                        <input type="checkbox" wire:model="is_active" class="h-4 w-4 rounded border-slate-300 text-[#0a5c2e] focus:ring-[#0a5c2e]">
                        Active (open for applications)
                    </label>
                </div>

                <div class="flex justify-end gap-3 border-t border-slate-200 px-5 py-4">
                    <button type="button" wire:click="closeModals" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</button>
                    <button type="submit" wire:loading.attr="disabled" wire:target="saveProgramme"
                            class="rounded-lg bg-[#0a5c2e] px-4 py-2 text-sm font-medium text-white hover:bg-[#0f7a3d] disabled:opacity-60">Save Programme</button>
                </div>
            </form>
        </div>
    @endif


    {{-- ---------------------------------------------------------------
         7d. CONFIRM DELETE (shows what will happen, or why it is blocked)
    ---------------------------------------------------------------- --}}
    @if ($deleteType !== '')
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" wire:click.self="closeModals" wire:keydown.escape.window="closeModals">
            <div class="w-full max-w-md rounded-xl bg-white shadow-xl">
                <div class="flex gap-4 px-5 py-5">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full {{ $canDelete ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-700' }}">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $canDelete ? $icons['trash'] : $icons['warning'] }}" /></svg>
                    </span>
                    <div>
                        <h3 class="font-semibold text-slate-900">
                            {{ $canDelete ? 'Delete ' . $deleteType . '?' : 'Cannot delete this ' . $deleteType }}
                        </h3>
                        <p class="mt-1 text-sm font-medium text-slate-800">{{ $deleteName }}</p>
                        <p class="mt-2 text-sm text-slate-600">{{ $deleteInfo }}</p>
                        @if ($canDelete)
                            <p class="mt-2 text-xs text-red-600">This action cannot be undone.</p>
                        @endif
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t border-slate-200 px-5 py-4">
                    <button type="button" wire:click="closeModals" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">
                        {{ $canDelete ? 'Cancel' : 'Close' }}
                    </button>
                    @if ($canDelete)
                        <button type="button" wire:click="delete" wire:loading.attr="disabled" wire:target="delete"
                                class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 disabled:opacity-60">Yes, delete</button>
                    @endif
                </div>
            </div>
        </div>
    @endif

</main>
