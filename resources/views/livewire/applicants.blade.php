{{--
    FILE: resources/views/livewire/applications.blade.php
    The sidebar and top bar come from the layout (app.blade.php), so this file only holds the page content.
    Livewire needs ONE root element: <main> below.
--}}
<main class="space-y-5 p-4 sm:p-6">

    {{-- =====================================================================
         0. ICONS (inline SVG paths, no package needed)
    ====================================================================== --}}
    @php
        $icons = [
            'search'   => 'M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z',
            'download' => 'M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3',
            'plus'     => 'M12 4.5v15m7.5-7.5h-15',
            'eye'      => 'M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178zM15 12a3 3 0 11-6 0 3 3 0 016 0z',
            'pencil'   => 'M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125',
            'left'     => 'M15.75 19.5L8.25 12l7.5-7.5',
            'right'    => 'M8.25 4.5l7.5 7.5-7.5 7.5',
            'x'        => 'M6 18L18 6M6 6l12 12',
            'document' => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z',
            'check'    => 'M4.5 12.75l6 6 9-13.5',
            'clock'    => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z',
            'chevron'  => 'M19.5 8.25l-7.5 7.5-7.5-7.5',
        ];

        // Badge colours for each status
        $statusClass = [
            'admitted' => 'bg-green-100 text-green-800 border-green-300',
            'pending'  => 'bg-yellow-100 text-yellow-800 border-yellow-300',
            'rejected' => 'bg-red-100 text-red-700 border-red-300',
        ];

        // The 4 summary cards. 'filter' is the status that gets applied when you click the card.
        $cards = [
            ['label' => 'Total Applications', 'value' => $counts['total'],    'filter' => '',         'icon' => 'document', 'card' => 'bg-[#e9f5ee] border-[#d3eadb]', 'iconBg' => 'bg-[#0a5c2e]'],
            ['label' => 'Admitted',           'value' => $counts['admitted'], 'filter' => 'admitted', 'icon' => 'check',    'card' => 'bg-[#e9f5ee] border-[#d3eadb]', 'iconBg' => 'bg-[#0a5c2e]'],
            ['label' => 'Pending',            'value' => $counts['pending'],  'filter' => 'pending',  'icon' => 'clock',    'card' => 'bg-[#faf3ea] border-[#f0e4d2]', 'iconBg' => 'bg-[#b8923e]'],
            ['label' => 'Rejected',           'value' => $counts['rejected'], 'filter' => 'rejected', 'icon' => 'x',        'card' => 'bg-[#faf3ea] border-[#f0e4d2]', 'iconBg' => 'bg-[#b8923e]'],
        ];

        $hasFilters = $status !== '' || $search !== '' || $session !== '';

        // Numbers taken from the paginator that Applicants.php sends ($applications)
        $total    = $applications->total();
        $page     = $applications->currentPage();
        $lastPage = $applications->lastPage();
        $from     = $applications->firstItem() ?? 0;
        $to       = $applications->lastItem() ?? 0;
    @endphp


    {{-- =====================================================================
         1. PAGE HEADER (breadcrumb, title, action buttons)
    ====================================================================== --}}
    <section class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <nav class="mb-1 flex items-center gap-1.5 text-xs text-slate-500">
                <a href="{{ url('/dashboard') }}" class="hover:text-[#0a5c2e]">Dashboard</a>
                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['right'] }}" /></svg>
                <span class="font-medium text-slate-700">Applications</span>
            </nav>
            <h1 class="text-2xl font-bold text-slate-900">Applications</h1>
            <p class="text-sm text-slate-500">
                Review and manage admission applications
                @if ($session !== '')
                    for the <span class="font-semibold text-slate-700">{{ $session }}</span> academic session.
                @else
                    across <span class="font-semibold text-slate-700">all academic sessions</span>.
                @endif
            </p>
        </div>

        <div class="flex items-center gap-3">
            {{-- Export button (connect it to your export route / Livewire method later) --}}
            <button type="button" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['download'] }}" /></svg>
                Export
            </button>
            <a href="#" class="inline-flex items-center gap-2 rounded-lg bg-[#0a5c2e] px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-[#0f7a3d]">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['plus'] }}" /></svg>
                New Application
            </a>
        </div>
    </section>


    {{-- =====================================================================
         2. SUMMARY CARDS (click a card to filter the table by that status)
    ====================================================================== --}}
    <section class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        @foreach ($cards as $c)
            <button type="button" wire:click="$set('status', '{{ $c['filter'] }}')"
                    class="rounded-xl border p-4 text-left transition hover:shadow-md {{ $c['card'] }}
                           {{ $status === $c['filter'] ? 'ring-2 ring-[#0a5c2e]/60' : '' }}">
                <div class="flex items-center gap-3">
                    <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl text-white {{ $c['iconBg'] }}">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[$c['icon']] }}" /></svg>
                    </span>
                    <div>
                        <p class="text-xs font-medium text-slate-700">{{ $c['label'] }}</p>
                        <p class="text-2xl font-bold text-slate-900">{{ number_format($c['value']) }}</p>
                    </div>
                </div>
            </button>
        @endforeach
    </section>


    {{-- =====================================================================
         3. FILTER BAR (search, academic session / year, status, rows per page)
    ====================================================================== --}}
    <section class="rounded-xl border border-slate-200 bg-white p-4">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-12 xl:items-end">

            {{-- 3a. Search --}}
            <div class="xl:col-span-4">
                <label for="search" class="mb-1 block text-xs font-medium text-slate-600">Search</label>
                <div class="relative">
                    <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['search'] }}" /></svg>
                    <input id="search" type="text" wire:model.live.debounce.400ms="search"
                           placeholder="Name, applicant no., application no. or programme"
                           class="w-full rounded-lg border border-slate-300 bg-white py-2 pl-9 pr-3 text-sm text-slate-800 placeholder-slate-400 focus:border-[#0a5c2e] focus:outline-none focus:ring-2 focus:ring-[#0a5c2e]/20">
                </div>
            </div>

            {{-- 3b. Academic session (year) select --}}
            <div class="xl:col-span-3">
                <label for="session" class="mb-1 block text-xs font-medium text-slate-600">Academic Session</label>
                <div class="relative">
                    <select id="session" wire:model.live="session"
                            class="w-full appearance-none rounded-lg border border-slate-300 bg-white py-2 pl-3 pr-9 text-sm text-slate-800 focus:border-[#0a5c2e] focus:outline-none focus:ring-2 focus:ring-[#0a5c2e]/20">
                        <option value="">All Sessions</option>
                        @foreach ($sessions as $s)
                            <option value="{{ $s }}">{{ $s }}</option>
                        @endforeach
                    </select>
                    <svg class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['chevron'] }}" /></svg>
                </div>
            </div>

            {{-- 3c. Status select --}}
            <div class="xl:col-span-2">
                <label for="status" class="mb-1 block text-xs font-medium text-slate-600">Status</label>
                <div class="relative">
                    <select id="status" wire:model.live="status"
                            class="w-full appearance-none rounded-lg border border-slate-300 bg-white py-2 pl-3 pr-9 text-sm text-slate-800 focus:border-[#0a5c2e] focus:outline-none focus:ring-2 focus:ring-[#0a5c2e]/20">
                        <option value="">All Statuses</option>
                        <option value="admitted">Admitted</option>
                        <option value="pending">Pending</option>
                        <option value="rejected">Rejected</option>
                    </select>
                    <svg class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['chevron'] }}" /></svg>
                </div>
            </div>

            {{-- 3d. Rows per page + clear --}}
            <div class="flex items-end gap-3 xl:col-span-3">
                <div class="flex-1">
                    <label for="perPage" class="mb-1 block text-xs font-medium text-slate-600">Rows per page</label>
                    <div class="relative">
                        <select id="perPage" wire:model.live="perPage"
                                class="w-full appearance-none rounded-lg border border-slate-300 bg-white py-2 pl-3 pr-9 text-sm text-slate-800 focus:border-[#0a5c2e] focus:outline-none focus:ring-2 focus:ring-[#0a5c2e]/20">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                        <svg class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['chevron'] }}" /></svg>
                    </div>
                </div>

                @if ($hasFilters)
                    <button type="button" wire:click="clearFilters"
                            class="inline-flex items-center gap-1 whitespace-nowrap rounded-lg border border-slate-300 px-3 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['x'] }}" /></svg>
                        Clear
                    </button>
                @endif
            </div>
        </div>
    </section>


    {{-- =====================================================================
         4. APPLICATIONS TABLE
    ====================================================================== --}}
    <section class="overflow-hidden rounded-xl border border-slate-200 bg-white">

        {{-- 4a. Table title --}}
        <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
            <h2 class="font-semibold text-slate-900">
                All Applications
                <span class="ml-2 rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">{{ number_format($total) }}</span>
            </h2>
            {{-- Small spinner while Livewire is fetching --}}
            <span wire:loading class="flex items-center gap-2 text-xs text-slate-500">
                <svg class="h-4 w-4 animate-spin text-[#0a5c2e]" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-25"/><path fill="currentColor" class="opacity-75" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
                Updating...
            </span>
        </div>

        {{-- 4b. Table --}}
        <div class="overflow-x-auto" wire:loading.class="opacity-60">
            <table class="w-full min-w-[900px] text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-600">
                    <tr>
                        <th class="px-5 py-3 font-semibold">Application No.</th>
                        <th class="px-5 py-3 font-semibold">Applicant</th>
                        <th class="px-5 py-3 font-semibold">Programme</th>
                        <th class="px-5 py-3 font-semibold">Session</th>
                        <th class="px-5 py-3 font-semibold">Status</th>
                        <th class="px-5 py-3 font-semibold">Date</th>
                        <th class="px-5 py-3 text-right font-semibold">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($applications as $row)
                        <tr wire:key="app-{{ $row->id }}" class="hover:bg-slate-50">
                            <td class="whitespace-nowrap px-5 py-3.5 font-medium text-[#0a5c2e]">{{ $row->application_number }}</td>

                            {{-- Applicant: initials avatar + full name + applicant number --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#e9f5ee] text-xs font-semibold text-[#0a5c2e]">
                                        {{ $row->applicant->initials }}
                                    </span>
                                    <div class="leading-tight">
                                        <p class="font-medium text-slate-900">{{ $row->applicant->full_name }}</p>
                                        <p class="text-xs text-slate-500">{{ $row->applicant->applicant_number }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-5 py-3.5 text-slate-700">{{ $row->programme->name }}</td>
                            <td class="whitespace-nowrap px-5 py-3.5 text-slate-700">{{ $row->academicSession->name }}</td>

                            <td class="px-5 py-3.5">
                                <span class="inline-block rounded-full border px-3 py-0.5 text-xs font-medium {{ $statusClass[$row->application_status] ?? 'bg-slate-100 text-slate-700 border-slate-300' }}">{{ ucfirst($row->application_status) }}</span>
                            </td>

                            <td class="whitespace-nowrap px-5 py-3.5 text-slate-700">{{ $row->application_date->format('d M Y') }}</td>

                            {{-- Row actions (point the hrefs to your real routes) --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="#" title="View" class="rounded-md p-2 text-slate-500 hover:bg-slate-100 hover:text-[#0a5c2e]">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['eye'] }}" /></svg>
                                    </a>
                                    <a href="#" title="Edit" class="rounded-md p-2 text-slate-500 hover:bg-slate-100 hover:text-[#0a5c2e]">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['pencil'] }}" /></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        {{-- 4c. Empty state --}}
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center">
                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['document'] }}" /></svg>
                                </div>
                                <p class="mt-3 text-sm font-semibold text-slate-800">No applications found</p>
                                <p class="mt-1 text-xs text-slate-500">Try a different session, status or search term.</p>
                                <button type="button" wire:click="clearFilters" class="mt-4 rounded-lg bg-[#0a5c2e] px-4 py-2 text-xs font-medium text-white hover:bg-[#0f7a3d]">Reset filters</button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- 4d. Footer: result count + pagination --}}
        <div class="flex flex-col items-center justify-between gap-3 border-t border-slate-200 px-5 py-4 sm:flex-row">
            <p class="text-xs text-slate-600">
                Showing <span class="font-semibold">{{ $from }}</span> to <span class="font-semibold">{{ $to }}</span>
                of <span class="font-semibold">{{ number_format($total) }}</span> results
            </p>

            @if ($lastPage > 1)
                @php
                    $start = max(1, $page - 2);
                    $end   = min($lastPage, $page + 2);
                @endphp
                <div class="flex items-center gap-1">
                    <button type="button" wire:click="gotoPage({{ $page - 1 }})" @disabled($page <= 1)
                            class="rounded-md border border-slate-300 p-2 text-slate-600 hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['left'] }}" /></svg>
                    </button>

                    @if ($start > 1)
                        <button type="button" wire:click="gotoPage(1)" class="min-w-[2.25rem] rounded-md border border-slate-300 px-2 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50">1</button>
                        @if ($start > 2) <span class="px-1 text-slate-400">...</span> @endif
                    @endif

                    @for ($p = $start; $p <= $end; $p++)
                        <button type="button" wire:click="gotoPage({{ $p }})"
                                class="min-w-[2.25rem] rounded-md border px-2 py-1.5 text-xs font-medium
                                       {{ $p === $page ? 'border-[#0a5c2e] bg-[#0a5c2e] text-white' : 'border-slate-300 text-slate-700 hover:bg-slate-50' }}">{{ $p }}</button>
                    @endfor

                    @if ($end < $lastPage)
                        @if ($end < $lastPage - 1) <span class="px-1 text-slate-400">...</span> @endif
                        <button type="button" wire:click="gotoPage({{ $lastPage }})" class="min-w-[2.25rem] rounded-md border border-slate-300 px-2 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50">{{ $lastPage }}</button>
                    @endif

                    <button type="button" wire:click="gotoPage({{ $page + 1 }})" @disabled($page >= $lastPage)
                            class="rounded-md border border-slate-300 p-2 text-slate-600 hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['right'] }}" /></svg>
                    </button>
                </div>
            @endif
        </div>
    </section>

</main>