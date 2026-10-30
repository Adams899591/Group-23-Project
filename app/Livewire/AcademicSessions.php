<?php

// namespace App\Livewire;

// use Livewire\Component;

// class AcademicSessions extends Component
// {
//     public function render()
//     {
//         return view('livewire.academic-sessions')->layout("layouts.app");
//     }
// }

// FILE: app/Livewire/AcademicSessions.php
// Lists all academic sessions and lets the admin add a new one.
// When a session is added, its id is also saved into programme_capacities and programme_cutoff_scores
// (one row for every programme), because those tables depend on academic_session_id.

namespace App\Livewire;

use App\Models\AcademicSession;
use App\Models\Programme;
use App\Models\ProgrammeCapacity;
use App\Models\ProgrammeCutoffScore;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Academic Sessions')]
class AcademicSessions extends Component
{
    // ---- Modal + form fields ----
    public bool $showModal = false;

    public string $start_year = '';               // e.g. 2027  -> session name "2027/2028"
    public string $application_start_date = '';
    public string $application_end_date = '';
    public string $copy_from = '';                // id of the session to copy capacities/cut-offs from ('' = start blank)

    // Open the "Add session" form with sensible defaults
    public function openModal(): void
    {
        $this->resetValidation();

        $latest = AcademicSession::orderByDesc('start_year')->first();   // newest existing session

        $this->start_year = (string) (($latest?->start_year ?? ((int) date('Y') - 1)) + 1);
        $this->copy_from  = (string) ($latest?->id ?? '');
        $this->setDefaultDates();

        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetValidation();
    }

    // When the year changes, suggest the usual window: 1 January to 31 August of that year
    public function updatedStartYear(): void
    {
        $this->setDefaultDates();
    }

    private function setDefaultDates(): void
    {
        if (preg_match('/^\d{4}$/', $this->start_year)) {
            $this->application_start_date = "{$this->start_year}-01-01";
            $this->application_end_date   = "{$this->start_year}-08-31";
        }
    }

    // ---- SAVE ----
    public function save(): void
    {
        $this->validate([
            // the session name is built from the year, so the year must not exist yet
            'start_year'             => ['required', 'integer', 'between:2000,2100', Rule::unique('academic_sessions', 'start_year')],
            'application_start_date' => ['nullable', 'date'],
            'application_end_date'   => ['nullable', 'date', 'after_or_equal:application_start_date'],
            'copy_from'              => ['nullable', 'exists:academic_sessions,id'],
        ], [
            'start_year.unique'                    => 'This academic session already exists.',
            'application_end_date.after_or_equal'  => 'The closing date must be on or after the opening date.',
        ], [
            'start_year'             => 'start year',
            'application_start_date' => 'opening date',
            'application_end_date'   => 'closing date',
        ]);

        $startYear = (int) $this->start_year;
        $now       = now();

        // Everything below happens together: if one step fails, nothing is saved.
        $programmeCount = DB::transaction(function () use ($startYear, $now) {

            // 1) The new session itself
            $session = AcademicSession::create([
                'name'                   => $startYear . '/' . ($startYear + 1),
                'start_year'             => $startYear,
                'end_year'               => $startYear + 1,
                'application_start_date' => $this->application_start_date ?: null,
                'application_end_date'   => $this->application_end_date ?: null,
            ]);

            // 2) Old values to copy from (keyed by programme_id). Empty when "Start blank" is chosen.
            $oldCapacities = $this->copy_from !== ''
                ? ProgrammeCapacity::where('academic_session_id', $this->copy_from)->pluck('approved_quota_capacity', 'programme_id')
                : collect();

            $oldCutoffs = $this->copy_from !== ''
                ? ProgrammeCutoffScore::where('academic_session_id', $this->copy_from)->get()->keyBy('programme_id')
                : collect();

            // 3) One capacity row + one cut-off row for EVERY programme, using the new session id
            $programmeIds = Programme::pluck('id');
            $capacityRows = [];
            $cutoffRows   = [];

            foreach ($programmeIds as $programmeId) {
                $capacityRows[] = [
                    'academic_session_id'     => $session->id,                      // <- the new session id
                    'programme_id'            => $programmeId,
                    'approved_quota_capacity' => $oldCapacities[$programmeId] ?? 0, // column is NOT NULL, so use 0 when blank
                    'created_at'              => $now,
                    'updated_at'              => $now,
                ];

                $cutoffRows[] = [
                    'academic_session_id'    => $session->id,                       // <- the new session id
                    'programme_id'           => $programmeId,
                    'utme_cutoff_score'      => $oldCutoffs[$programmeId]->utme_cutoff_score ?? null,
                    'post_utme_cutoff_score' => $oldCutoffs[$programmeId]->post_utme_cutoff_score ?? null,
                    'aggregate_cutoff_score' => $oldCutoffs[$programmeId]->aggregate_cutoff_score ?? null,
                    'created_at'             => $now,
                    'updated_at'             => $now,
                ];
            }

            ProgrammeCapacity::insert($capacityRows);
            ProgrammeCutoffScore::insert($cutoffRows);

            return $programmeIds->count();
        });

        $this->closeModal();
        session()->flash('success', "Academic session {$startYear}/" . ($startYear + 1) . " added with capacity and cut-off records for {$programmeCount} programmes.");
    }

    // ---- RENDER ----
    public function render()
    {
        // All sessions, newest first, with the numbers shown in the table (all through relationships)
        $sessions = AcademicSession::query()
            ->withCount([
                'applications',
                'applications as admitted_count' => fn ($q) => $q->where('application_status', 'admitted'),
            ])
            ->withSum('capacities as total_capacity', 'approved_quota_capacity')
            ->orderByDesc('start_year')
            ->get();

        return view('livewire.academic-sessions', [
            'sessions'       => $sessions,
            'programmeCount' => Programme::count(),
        ])->layout('layouts.app');
    }
}