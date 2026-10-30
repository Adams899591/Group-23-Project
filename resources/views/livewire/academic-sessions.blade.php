{{--
    FILE: resources/views/livewire/academic-sessions.blade.php
    Shows all academic sessions and lets the admin add a new one.
    The sidebar and top bar come from the layout. Livewire needs ONE root element: <main>.
--}}
<main class="space-y-5 p-4 sm:p-6">

    {{-- =====================================================================
         0. ICONS + DATA PREPARED FOR THE PAGE
    ====================================================================== --}}
    @php
        $icons = [
            'plus'     => 'M12 4.5v15m7.5-7.5h-15',
            'x'        => 'M6 18L18 6M6 6l12 12',
            'right'    => 'M8.25 4.5l7.5 7.5-7.5 7.5',
            'chevron'  => 'M19.5 8.25l-7.5 7.5-7.5-7.5',
            'calendar' => 'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5',
            'document' => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z',
            'check'    => 'M4.5 12.75l6 6 9-13.5',
            'clock'    => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z',
            'info'     => 'M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z',
        ];

        // The session that is accepting applications right now (if any)
        $openSession = $sessions->firstWhere('status', 'Open');

        // 4 cards at the top
        $cards = [
            ['label' => 'Total Sessions',     'value' => $sessions->count(),                  'icon' => 'calendar', 'card' => 'bg-[#e9f5ee] border-[#d3eadb]', 'iconBg' => 'bg-[#0a5c2e]'],
            ['label' => 'Open for Applications', 'value' => $openSession?->name ?? 'None',    'icon' => 'clock',    'card' => 'bg-[#faf3ea] border-[#f0e4d2]', 'iconBg' => 'bg-[#b8923e]'],
            ['label' => 'Total Applications', 'value' => number_format($sessions->sum('applications_count')), 'icon' => 'document', 'card' => 'bg-[#e9f5ee] border-[#d3eadb]', 'iconBg' => 'bg-[#0a5c2e]'],
            ['label' => 'Total Admitted',     'value' => number_format($sessions->sum('admitted_count')),     'icon' => 'check',    'card' => 'bg-[#faf3ea] border-[#f0e4d2]', 'iconBg' => 'bg-[#b8923e]'],
        ];

        // Badge colours for the session status
        $statusClass = [
            'Open'     => 'border-green-300 bg-green-100 text-green-800',
            'Upcoming' => 'border-yellow-300 bg-yellow-100 text-yellow-800',
            'Closed'   => 'border-slate-300 bg-slate-100 text-slate-600',
            'Not set'  => 'border-slate-300 bg-slate-100 text-slate-500',
        ];

        // Same look for inputs in the form
        $input  = 'w-full rounded-lg border border-slate-300 bg-white py-2 px-3 text-sm text-slate-800 placeholder-slate-400 focus:border-[#0a5c2e] focus:outline-none focus:ring-2 focus:ring-[#0a5c2e]/20';
        $select = 'w-full appearance-none rounded-lg border border-slate-300 bg-white py-2 pl-3 pr-9 text-sm text-slate-800 focus:border-[#0a5c2e] focus:outline-none focus:ring-2 focus:ring-[#0a5c2e]/20';

        // Name of the session being created (live preview inside the form)
        $previewName = preg_match('/^\d{4}$/', $start_year) ? $start_year . '/' . ((int) $start_year + 1) : '-';
    @endphp


    {{-- =====================================================================
         1. SUCCESS MESSAGE (disappears after 5 seconds)
    ====================================================================== --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition
             class="flex items-center justify-between rounded-lg border border-green-300 bg-green-50 px-4 py-3 text-sm text-green-800">
            <span>{{ session('success') }}</span>
            <button type="button" @click="show = false" class="ml-4 opacity-60 hover:opacity-100">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['x'] }}" /></svg>
            </button>
        </div>
    @endif


    {{-- =====================================================================
         2. PAGE HEADER (breadcrumb, title, Add button)
    ====================================================================== --}}
    <section class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <nav class="mb-1 flex items-center gap-1.5 text-xs text-slate-500">
                <a href="{{ url('/dashboard') }}" class="hover:text-[#0a5c2e]">Dashboard</a>
                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['right'] }}" /></svg>
                <span class="font-medium text-slate-700">Academic Sessions</span>
            </nav>
            <h1 class="text-2xl font-bold text-slate-900">Academic Sessions</h1>
            <p class="text-sm text-slate-500">All admission sessions with their application window, applications and approved capacity.</p>
        </div>

        <button type="button" wire:click="openModal"
                class="inline-flex items-center gap-2 rounded-lg bg-[#0a5c2e] px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-[#0f7a3d]">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['plus'] }}" /></svg>
            Add Session
        </button>
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
                    <div class="min-w-0">
                        <p class="truncate text-xs font-medium text-slate-700">{{ $c['label'] }}</p>
                        <p class="truncate text-2xl font-bold text-slate-900">{{ $c['value'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </section>


    {{-- =====================================================================
         4. SESSIONS TABLE
    ====================================================================== --}}
    <section class="overflow-hidden rounded-xl border border-slate-200 bg-white">
        <div class="border-b border-slate-200 px-5 py-4">
            <h2 class="font-semibold text-slate-900">
                All Sessions
                <span class="ml-2 rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">{{ $sessions->count() }}</span>
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[820px] text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-600">
                    <tr>
                        <th class="px-5 py-3 font-semibold">Session</th>
                        <th class="px-5 py-3 font-semibold">Application Window</th>
                        <th class="px-5 py-3 font-semibold">Status</th>
                        <th class="px-5 py-3 text-right font-semibold">Applications</th>
                        <th class="px-5 py-3 text-right font-semibold">Admitted</th>
                        <th class="px-5 py-3 text-right font-semibold">Approved Capacity</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($sessions as $s)
                        <tr wire:key="session-{{ $s->id }}" class="hover:bg-slate-50">
                            <td class="px-5 py-3.5">
                                <p class="font-semibold text-slate-900">{{ $s->name }}</p>
                                <p class="text-xs text-slate-500">{{ $s->start_year }} - {{ $s->end_year }}</p>
                            </td>

                            <td class="whitespace-nowrap px-5 py-3.5 text-slate-700">
                                @if ($s->application_start_date && $s->application_end_date)
                                    {{ $s->application_start_date->format('d M Y') }} - {{ $s->application_end_date->format('d M Y') }}
                                @else
                                    <span class="text-slate-400">Not set</span>
                                @endif
                            </td>

                            <td class="px-5 py-3.5">
                                <span class="inline-block rounded-full border px-3 py-0.5 text-xs font-medium {{ $statusClass[$s->status] }}">{{ $s->status }}</span>
                            </td>

                            <td class="px-5 py-3.5 text-right text-slate-700">{{ number_format($s->applications_count) }}</td>
                            <td class="px-5 py-3.5 text-right text-slate-700">{{ number_format($s->admitted_count) }}</td>
                            <td class="px-5 py-3.5 text-right text-slate-700">{{ number_format($s->total_capacity ?? 0) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center">
                                <p class="text-sm font-semibold text-slate-800">No academic sessions yet</p>
                                <p class="mt-1 text-xs text-slate-500">Click "Add Session" to create the first one.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>


    {{-- =====================================================================
         5. ADD SESSION MODAL (click the dark background or press Esc to close)
    ====================================================================== --}}
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-black/50 p-4" wire:click.self="closeModal" wire:keydown.escape.window="closeModal">
            <form wire:submit="save" class="w-full max-w-lg rounded-xl bg-white shadow-xl">

                {{-- 5a. Title --}}
                <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                    <h3 class="font-semibold text-slate-900">Add Academic Session</h3>
                    <button type="button" wire:click="closeModal" class="text-slate-400 hover:text-slate-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['x'] }}" /></svg>
                    </button>
                </div>

                <div class="space-y-4 px-5 py-5">

                    {{-- 5b. Start year + live preview of the session name --}}
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-600">Start year</label>
                            <input type="number" wire:model.live.debounce.400ms="start_year" placeholder="e.g. 2027" class="{{ $input }}">
                            @error('start_year') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-600">Session name</label>
                            <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-semibold text-[#0a5c2e]">{{ $previewName }}</div>
                        </div>
                    </div>

                    {{-- 5c. Application window --}}
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-600">Applications open</label>
                            <input type="date" wire:model="application_start_date" class="{{ $input }}">
                            @error('application_start_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="mb-1 block text-xs font-medium text-slate-600">Applications close</label>
                            <input type="date" wire:model="application_end_date" class="{{ $input }}">
                            @error('application_end_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- 5d. Copy capacity and cut-off scores from an older session --}}
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-600">Copy capacity and cut-off scores from</label>
                        <div class="relative">
                            <select wire:model="copy_from" class="{{ $select }}">
                                <option value="">Start blank (capacity 0, no cut-off scores)</option>
                                @foreach ($sessions as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select>
                            <svg class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['chevron'] }}" /></svg>
                        </div>
                        @error('copy_from') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- 5e. What happens in the database --}}
                    <div class="flex gap-3 rounded-lg border border-[#d3eadb] bg-[#e9f5ee] px-4 py-3 text-xs text-slate-700">
                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-[#0a5c2e]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons['info'] }}" /></svg>
                        <p>
                            The new session id is also saved in <span class="font-semibold">programme capacities</span> and
                            <span class="font-semibold">programme cut-off scores</span>: one record for each of your
                            <span class="font-semibold">{{ $programmeCount }}</span> programmes. You can edit the numbers later.
                        </p>
                    </div>
                </div>

                {{-- 5f. Buttons --}}
                <div class="flex justify-end gap-3 border-t border-slate-200 px-5 py-4">
                    <button type="button" wire:click="closeModal" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</button>
                    <button type="submit" wire:loading.attr="disabled" wire:target="save"
                            class="rounded-lg bg-[#0a5c2e] px-4 py-2 text-sm font-medium text-white hover:bg-[#0f7a3d] disabled:opacity-60">Save Session</button>
                </div>
            </form>
        </div>
    @endif

</main>