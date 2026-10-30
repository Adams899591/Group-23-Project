<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Programme extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];
    
     // ======== Relationship ==========
 
    // programmes.department_id -> departments.id   ($programme->department->faculty->name)
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
 
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }
 
    // programme_capacities.programme_id
    public function capacities(): HasMany
    {
        return $this->hasMany(ProgrammeCapacitie::class);
    }
 
    // programme_cutoff_scores.programme_id
    public function cutoffScores(): HasMany
    {
        return $this->hasMany(ProgrammeCutoffScore::class);
    }

}
