<?php

// FILE: app/Livewire/Applicants.php
// Uses Eloquent models and their relationships (Application -> applicant, programme, academicSession).

namespace App\Livewire;

use App\Models\AcademicSession;
use App\Models\Application;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Applications')]
class Applicants extends Component
{
    use WithPagination;   // gives us paginate(), gotoPage() and resetPage()

    // ---- Filters (kept in the URL, e.g. /applications?session=2025/2026&status=admitted) ----
    #[Url(as: 'session')]
    public string $session = '';      // academic session name, '' = All Sessions

    #[Url(as: 'status')]
    public string $status = '';       // '', admitted, pending, rejected (lowercase, same as the database)

    #[Url(as: 'q')]
    public string $search = '';

    public int $perPage = 10;

    // When any filter changes, go back to page 1
    public function updatedSession(): void { $this->resetPage(); }
    public function updatedStatus(): void  { $this->resetPage(); }
    public function updatedSearch(): void  { $this->resetPage(); }
    public function updatedPerPage(): void { $this->resetPage(); }

    public function clearFilters(): void
    {
        $this->reset(['session', 'status', 'search']);
        $this->resetPage();
    }

    public function render()
    {
        // Only allow safe values for "rows per page"
        $perPage = in_array($this->perPage, [10, 25, 50], true) ? $this->perPage : 10;

        // 1) BASE QUERY: session filter + search (the 4 cards use this, so they show every status)
        $base = Application::query()
            // Session filter: goes through the academicSession() relationship
            ->when($this->session !== '', fn ($q) =>
                $q->whereHas('academicSession', fn ($s) => $s->where('name', $this->session))
            )
            // Search: application number, or applicant (number / full name), or programme name
            ->when($this->search !== '', function ($q) {
                $term = '%' . trim($this->search) . '%';

                $q->where(function ($w) use ($term) {
                    $w->where('application_number', 'like', $term)
                      ->orWhereHas('applicant', fn ($a) => $a
                          ->where('applicant_number', 'like', $term)
                          ->orWhereRaw("CONCAT_WS(' ', first_name, middle_name, last_name) LIKE ?", [$term]))
                      ->orWhereHas('programme', fn ($p) => $p->where('name', 'like', $term));
                });
            });

        // 2) COUNTS for the summary cards (one grouped query)
        $byStatus = (clone $base)
            ->selectRaw('application_status, COUNT(*) as total')
            ->groupBy('application_status')
            ->pluck('total', 'application_status');

        $counts = [
            'total'    => $byStatus->sum(),
            'admitted' => $byStatus['admitted'] ?? 0,
            'pending'  => $byStatus['pending']  ?? 0,
            'rejected' => $byStatus['rejected'] ?? 0,
        ];

        // 3) TABLE ROWS: status filter + eager load the relationships (avoids slow N+1 queries)
        $applications = (clone $base)
            ->with(['applicant', 'programme', 'academicSession'])
            ->when(
                in_array($this->status, ['admitted', 'pending', 'rejected'], true),
                fn ($q) => $q->where('application_status', $this->status)
            )
            ->latest('application_date')   // newest application first
            ->latest('id')
            ->paginate($perPage);

        return view('livewire.applicants', [
            'applications' => $applications,
            'counts'       => $counts,
            // Options of the "Academic Session" select (newest first)
            'sessions'     => AcademicSession::orderByDesc('start_year')->pluck('name'),
        ])->layout("layouts.app");
    }
}