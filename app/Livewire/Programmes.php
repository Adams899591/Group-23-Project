<?php

namespace App\Livewire;

use App\Models\Department;
use App\Models\Faculty;
use App\Models\Programme;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Programmes')]
class Programmes extends Component
{
    use WithPagination;

    // =====================================================================
    // 1. TABS + FILTERS (kept in the URL)
    // =====================================================================
    #[Url(as: 'tab')]
    public string $tab = 'programmes';        // programmes | departments | faculties

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'faculty')]
    public string $facultyFilter = '';        // faculty id, '' = all faculties

    #[Url(as: 'status')]
    public string $statusFilter = '';         // '', active, inactive (programmes tab only)

    // =====================================================================
    // 2. MODAL STATE
    // =====================================================================
    public bool $showFacultyModal = false;
    public bool $showDepartmentModal = false;
    public bool $showProgrammeModal = false;

    // =====================================================================
    // 3. FORM FIELDS (one group per modal)
    // =====================================================================
    // Faculty form -> faculties(name, code)
    public string $faculty_name = '';
    public string $faculty_code = '';

    // Department form -> departments(faculty_id, name, code)
    public string $department_faculty_id = '';
    public string $department_name = '';
    public string $department_code = '';

    // Programme form -> programmes(department_id, name, degree_award_type, duration_years, is_active)
    // programme_faculty_id is only used to filter the department dropdown (it is not saved).
    public string $programme_faculty_id = '';
    public string $programme_department_id = '';
    public string $programme_name = '';
    public string $degree_award_type = 'B.Sc.';
    public string $duration_years = '4';
    public bool $is_active = true;

    // =====================================================================
    // 4. DELETE CONFIRMATION STATE
    // =====================================================================
    public string $deleteType = '';           // programme | department | faculty ('' = closed)
    public int $deleteId = 0;
    public string $deleteName = '';
    public string $deleteInfo = '';           // explains what will happen / why it is blocked
    public bool $canDelete = false;

    // ---------------------------------------------------------------------
    // Small helpers
    // ---------------------------------------------------------------------
    public function setTab(string $tab): void
    {
        $this->tab = in_array($tab, ['programmes', 'departments', 'faculties'], true) ? $tab : 'programmes';
        $this->reset(['search', 'facultyFilter', 'statusFilter']);
        $this->resetPage();
    }

    public function updatedSearch(): void        { $this->resetPage(); }
    public function updatedFacultyFilter(): void { $this->resetPage(); }
    public function updatedStatusFilter(): void  { $this->resetPage(); }

    // When the faculty changes inside the programme form, clear the old department
    public function updatedProgrammeFacultyId(): void
    {
        $this->programme_department_id = '';
    }

    public function closeModals(): void
    {
        $this->showFacultyModal = false;
        $this->showDepartmentModal = false;
        $this->showProgrammeModal = false;
        $this->reset(['deleteType', 'deleteId', 'deleteName', 'deleteInfo', 'canDelete']);
        $this->resetValidation();
    }

    // =====================================================================
    // 5. ADD FACULTY
    // =====================================================================
    public function openFacultyModal(): void
    {
        $this->closeModals();
        $this->reset(['faculty_name', 'faculty_code']);
        $this->showFacultyModal = true;
    }

    public function saveFaculty(): void
    {
        $this->validate([
            'faculty_name' => ['required', 'string', 'max:255', Rule::unique('faculties', 'name')],
            'faculty_code' => ['nullable', 'string', 'max:255', Rule::unique('faculties', 'code')],
        ], [
            'faculty_name.unique' => 'This faculty already exists.',
            'faculty_code.unique' => 'This faculty code is already used.',
        ], [
            'faculty_name' => 'faculty name',
            'faculty_code' => 'faculty code',
        ]);

        Faculty::create([
            'name' => trim($this->faculty_name),
            'code' => trim($this->faculty_code) ?: null,   // empty code is saved as NULL (the code column is unique)
        ]);

        $this->closeModals();
        session()->flash('success', 'Faculty added successfully.');
    }

    // =====================================================================
    // 6. ADD DEPARTMENT
    // =====================================================================
    public function openDepartmentModal(): void
    {
        $this->closeModals();
        $this->reset(['department_faculty_id', 'department_name', 'department_code']);
        $this->showDepartmentModal = true;
    }

    public function saveDepartment(): void
    {
        $this->validate([
            'department_faculty_id' => ['required', 'exists:faculties,id'],
            'department_name'       => [
                'required', 'string', 'max:255',
                // a department name must be unique inside its faculty (departments_faculty_id_name_unique)
                Rule::unique('departments', 'name')->where('faculty_id', $this->department_faculty_id),
            ],
            'department_code'       => ['nullable', 'string', 'max:255', Rule::unique('departments', 'code')],
        ], [
            'department_faculty_id.required' => 'Please select a faculty.',
            'department_name.unique'         => 'This department already exists in the selected faculty.',
            'department_code.unique'         => 'This department code is already used.',
        ], [
            'department_name' => 'department name',
            'department_code' => 'department code',
        ]);

        Department::create([
            'faculty_id' => $this->department_faculty_id,
            'name'       => trim($this->department_name),
            'code'       => trim($this->department_code) ?: null,
        ]);

        $this->closeModals();
        session()->flash('success', 'Department added successfully.');
    }

    // =====================================================================
    // 7. ADD PROGRAMME
    // =====================================================================
    public function openProgrammeModal(): void
    {
        $this->closeModals();
        $this->reset([
            'programme_faculty_id', 'programme_department_id', 'programme_name',
            'degree_award_type', 'duration_years', 'is_active',
        ]);
        $this->showProgrammeModal = true;
    }

    public function saveProgramme(): void
    {
        $this->validate([
            'programme_faculty_id'    => ['required', 'exists:faculties,id'],
            // the department must belong to the chosen faculty
            'programme_department_id' => [
                'required',
                Rule::exists('departments', 'id')->where('faculty_id', $this->programme_faculty_id),
            ],
            // a programme name must be unique inside its department (programmes_department_id_name_unique)
            'programme_name'          => [
                'required', 'string', 'max:255',
                Rule::unique('programmes', 'name')->where('department_id', $this->programme_department_id),
            ],
            'degree_award_type'       => ['nullable', 'string', 'max:255'],
            'duration_years'          => ['required', 'integer', 'between:1,8'],
            'is_active'               => ['boolean'],
        ], [
            'programme_faculty_id.required'    => 'Please select a faculty.',
            'programme_department_id.required' => 'Please select a department.',
            'programme_department_id.exists'   => 'That department does not belong to the selected faculty.',
            'programme_name.unique'            => 'This programme already exists in the selected department.',
        ], [
            'programme_name'    => 'programme name',
            'degree_award_type' => 'degree',
            'duration_years'    => 'duration',
        ]);

        Programme::create([
            'department_id'     => $this->programme_department_id,
            'name'              => trim($this->programme_name),
            'degree_award_type' => trim($this->degree_award_type) ?: null,
            'duration_years'    => (int) $this->duration_years,
            'is_active'         => $this->is_active,
        ]);

        $this->closeModals();
        session()->flash('success', 'Programme added successfully.');
    }

    // =====================================================================
    // 8. DELETE (step 1: ask for confirmation and explain what will happen)
    // =====================================================================
    public function confirmDelete(string $type, int $id): void
    {
        $this->closeModals();

        if ($type === 'programme' && ($item = Programme::withCount(['applications', 'capacities', 'cutoffScores'])->find($id))) {
            $this->deleteName = $item->name;

            if ($item->applications_count > 0) {
                // applications.programme_id has a foreign key, so we must not delete it
                $this->canDelete  = false;
                $this->deleteInfo = "This programme has {$item->applications_count} application(s), so it cannot be deleted. You can edit it to inactive later instead.";
            } else {
                $this->canDelete  = true;
                $this->deleteInfo = "Its {$item->capacities_count} capacity record(s) and {$item->cutoff_scores_count} cut-off score record(s) will be deleted too.";
            }
        } elseif ($type === 'department' && ($item = Department::withCount('programmes')->find($id))) {
            $this->deleteName = $item->name;
            $this->canDelete  = $item->programmes_count === 0;
            $this->deleteInfo = $this->canDelete
                ? 'This department has no programmes, so it can be deleted safely.'
                : "This department still has {$item->programmes_count} programme(s). Delete or move them first.";
        } elseif ($type === 'faculty' && ($item = Faculty::withCount('departments')->find($id))) {
            $this->deleteName = $item->name;
            $this->canDelete  = $item->departments_count === 0;
            $this->deleteInfo = $this->canDelete
                ? 'This faculty has no departments, so it can be deleted safely.'
                : "This faculty still has {$item->departments_count} department(s). Delete them first.";
        } else {
            session()->flash('error', 'That record no longer exists.');
            return;
        }

        $this->deleteType = $type;
        $this->deleteId   = $id;
    }

    // DELETE (step 2: really delete)
    public function delete(): void
    {
        if (! $this->canDelete) {
            return;
        }

        try {
            DB::transaction(function () {
                if ($this->deleteType === 'programme') {
                    $programme = Programme::withCount('applications')->findOrFail($this->deleteId);
                    abort_if($programme->applications_count > 0, 409);   // safety check again

                    $programme->capacities()->delete();      // programme_capacities rows
                    $programme->cutoffScores()->delete();    // programme_cutoff_scores rows
                    $programme->delete();
                } elseif ($this->deleteType === 'department') {
                    $department = Department::withCount('programmes')->findOrFail($this->deleteId);
                    abort_if($department->programmes_count > 0, 409);
                    $department->delete();
                } elseif ($this->deleteType === 'faculty') {
                    $faculty = Faculty::withCount('departments')->findOrFail($this->deleteId);
                    abort_if($faculty->departments_count > 0, 409);
                    $faculty->delete();
                }
            });

            $name = $this->deleteName;
            $this->closeModals();
            $this->resetPage();
            session()->flash('success', "\"{$name}\" was deleted.");
        } catch (QueryException | \RuntimeException $e) {
            // Foreign key problem, or the record changed / disappeared while the dialog was open
            $this->closeModals();
            session()->flash('error', 'Could not delete this record because other records depend on it.');
        }
    }

    // =====================================================================
    // 9. RENDER
    // =====================================================================
    public function render()
    {
        $term = '%' . trim($this->search) . '%';
        $hasSearch = trim($this->search) !== '';

        // ---- Programmes (paginated) with department + faculty through the relationships ----
        $programmes = Programme::query()
            ->with('department.faculty')
            ->withCount('applications')
            ->when($hasSearch, fn ($q) => $q->where(function ($w) use ($term) {
                $w->where('name', 'like', $term)
                  ->orWhereHas('department', fn ($d) => $d
                      ->where('name', 'like', $term)
                      ->orWhereHas('faculty', fn ($f) => $f->where('name', 'like', $term)));
            }))
            ->when($this->facultyFilter !== '', fn ($q) =>
                $q->whereHas('department', fn ($d) => $d->where('faculty_id', $this->facultyFilter))
            )
            ->when($this->statusFilter !== '', fn ($q) =>
                $q->where('is_active', $this->statusFilter === 'active')
            )
            ->orderBy('name')
            ->paginate(10);

        // ---- Departments (small list, no pagination) ----
        $departments = Department::query()
            ->with('faculty')
            ->withCount('programmes')
            ->when($hasSearch, fn ($q) => $q->where(function ($w) use ($term) {
                $w->where('name', 'like', $term)
                  ->orWhere('code', 'like', $term)
                  ->orWhereHas('faculty', fn ($f) => $f->where('name', 'like', $term));
            }))
            ->when($this->facultyFilter !== '', fn ($q) => $q->where('faculty_id', $this->facultyFilter))
            ->orderBy('faculty_id')
            ->orderBy('name')
            ->get();

        // ---- Faculties ----
        $faculties = Faculty::query()
            ->withCount(['departments', 'programmes'])
            ->when($hasSearch, fn ($q) => $q->where(fn ($w) => $w->where('name', 'like', $term)->orWhere('code', 'like', $term)))
            ->orderBy('name')
            ->get();

        return view('livewire.programmes', [
            'programmes'        => $programmes,
            'departments'       => $departments,
            'faculties'         => $faculties,
            'facultyOptions'    => Faculty::orderBy('name')->get(['id', 'name']),
            // departments of the faculty chosen inside the "Add Programme" form
            'departmentOptions' => $this->programme_faculty_id !== ''
                ? Department::where('faculty_id', $this->programme_faculty_id)->orderBy('name')->get(['id', 'name'])
                : collect(),
            // the 4 cards at the top
            'stats'             => [
                'faculties'   => Faculty::count(),
                'departments' => Department::count(),
                'programmes'  => Programme::count(),
                'active'      => Programme::where('is_active', true)->count(),
            ],
        ])->layout("layouts.app");
    }
}