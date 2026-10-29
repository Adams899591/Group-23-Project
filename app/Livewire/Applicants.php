<?php

// namespace App\Livewire;

// use Livewire\Component;

// class Applicants extends Component
// {
//     public function render()
//     {
//         return view('livewire.applicants')->layout("layouts.app");
//     }
// }




// FILE: app/Livewire/Applications.php
// Create it with:  php artisan make:livewire applications   (then replace the generated class with this)

namespace App\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Title('Applicants')]
class Applicants extends Component
{
    // ---- Filters (kept in the URL so the page can be bookmarked / refreshed) ----
    #[Url(as: 'session')]
    public string $session = '2025/2026';   // '' = All Sessions

    #[Url(as: 'status')]
    public string $status = '';             // '', Admitted, Pending, Rejected

    #[Url(as: 'q')]
    public string $search = '';

    public int $perPage = 10;
    public int $page = 1;

    // Go back to page 1 whenever a filter changes
    public function updatedSession(): void  { $this->page = 1; }
    public function updatedStatus(): void   { $this->page = 1; }
    public function updatedSearch(): void   { $this->page = 1; }
    public function updatedPerPage(): void  { $this->page = 1; }

    public function gotoPage(int $page): void { $this->page = max(1, $page); }

    public function clearFilters(): void
    {
        $this->reset(['status', 'search']);
        $this->session = '2025/2026';
        $this->page = 1;
    }

    /**
     * Academic sessions shown in the "Academic Session" dropdown.
     * Later: AcademicSession::orderByDesc('name')->pluck('name')->all();
     */
    private function sessions(): array
    {
        return ['2025/2026', '2024/2025', '2023/2024', '2022/2023'];
    }

    /**
     * PLACEHOLDER DATA. Replace this whole method with your database query, e.g.
     *
     *   Application::with(['applicant', 'programme'])
     *       ->when($this->session, fn ($q) => $q->whereHas('session', fn ($s) => $s->where('name', $this->session)))
     *       ->when($this->status,  fn ($q) => $q->where('status', $this->status))
     *       ->when($this->search,  fn ($q) => $q->where('application_no', 'like', "%{$this->search}%"))
     *       ->latest()->paginate($this->perPage);
     */
    private function sampleData(): Collection
    {
        $names = [
            'Usman Ahmed', 'Aisha Ibrahim', 'Musa Bello', 'Fatima Sani', 'Ibrahim Yusuf',
            'Zainab Abdullahi', 'Abdulrahman Garba', 'Hauwa Lawal', 'Sani Mohammed', 'Khadija Usman',
            'Yusuf Danjuma', 'Maryam Aliyu', 'Bashir Umar', 'Halima Idris', 'Suleiman Tanko',
        ];
        $programmes = [
            'B.Sc. Computer Science', 'B.Eng. Electrical Engineering', 'B.Sc. Accounting', 'B.Sc. Mathematics',
            'B.Sc. Business Administration', 'B.Sc. Software Engineering', 'B.Eng. Civil Engineering', 'B.Sc. Economics',
        ];
        $statuses = ['Admitted', 'Pending', 'Rejected', 'Admitted', 'Pending', 'Admitted'];
        $sessions = $this->sessions();

        $rows = collect();
        for ($i = 1; $i <= 120; $i++) {
            $session = $sessions[$i % count($sessions)];
            $name    = $names[$i % count($names)];
            $year    = (int) substr($session, 0, 4);

            $rows->push([
                'no'        => 'APPN' . str_pad((string) (1400 - $i), 7, '0', STR_PAD_LEFT),
                'name'      => $name,
                'email'     => strtolower(str_replace(' ', '.', $name)) . $i . '@example.com',
                'programme' => $programmes[$i % count($programmes)],
                'session'   => $session,
                'status'    => $statuses[$i % count($statuses)],
                'date'      => Carbon::create($year, 1, 1)->addDays(($i * 7) % 300)->format('Y-m-d'),
            ]);
        }

        return $rows->sortByDesc('date')->values();
    }

    public function render()
    {
        $all = $this->sampleData();

        // 1) Session + search (used for the 4 summary cards, so they stay stable when you click a status)
        $scoped = $all
            ->when($this->session !== '', fn ($c) => $c->where('session', $this->session))
            ->when($this->search !== '', function ($c) {
                $term = strtolower($this->search);
                return $c->filter(fn ($r) => str_contains(strtolower($r['name'] . ' ' . $r['no'] . ' ' . $r['programme']), $term));
            });

        $counts = [
            'total'    => $scoped->count(),
            'Admitted' => $scoped->where('status', 'Admitted')->count(),
            'Pending'  => $scoped->where('status', 'Pending')->count(),
            'Rejected' => $scoped->where('status', 'Rejected')->count(),
        ];

        // 2) Status filter + pagination for the table
        $filtered = $scoped->when($this->status !== '', fn ($c) => $c->where('status', $this->status))->values();

        $total    = $filtered->count();
        $lastPage = max(1, (int) ceil($total / $this->perPage));
        $page     = min($this->page, $lastPage);
        $rows     = $filtered->slice(($page - 1) * $this->perPage, $this->perPage)->values();

        return view('livewire.applicants', [
            'rows'     => $rows,
            'total'    => $total,
            'page'     => $page,
            'lastPage' => $lastPage,
            'from'     => $total ? (($page - 1) * $this->perPage) + 1 : 0,
            'to'       => min($page * $this->perPage, $total),
            'counts'   => $counts,
            'sessions' => $this->sessions(),
        ])->layout("layouts.app");
    }
}