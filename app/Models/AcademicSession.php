<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Relations\HasMany;

// class AcademicSession extends Model
// {
//       protected $guarded = [];

//       protected $casts = [
//         'application_start_date' => 'date',
//         'application_end_date'   => 'date',
//     ];
 
//     // One session has many applications
//     public function applications(): HasMany
//     {
//         return $this->hasMany(Application::class);
//     }
// }


// FILE: app/Models/AcademicSession.php  (table: academic_sessions)

namespace App\Models;

use App\Models\ProgrammeCapacitie;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicSession extends Model
{
    protected $guarded = [];

    protected $casts = [
        'application_start_date' => 'date',
        'application_end_date'   => 'date',
    ];

    // applications.academic_session_id
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    // programme_capacities.academic_session_id (one row per programme)
    public function capacities(): HasMany
    {
        return $this->hasMany(ProgrammeCapacitie::class);
    }

    // programme_cutoff_scores.academic_session_id (one row per programme)
    public function cutoffScores(): HasMany
    {
        return $this->hasMany(ProgrammeCutoffScore::class);
    }

    // $session->status  ->  Open | Upcoming | Closed | Not set   (based on the application dates)
    protected function status(): Attribute
    {
        return Attribute::get(function () {
            if (! $this->application_start_date || ! $this->application_end_date) {
                return 'Not set';
            }

            $today = now()->startOfDay();

            if ($today->lt($this->application_start_date)) return 'Upcoming';
            if ($today->gt($this->application_end_date))   return 'Closed';

            return 'Open';
        });
    }
}