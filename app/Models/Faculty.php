<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Faculty extends Model
{
       protected $guarded = [];

   public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }
 
    // Faculty -> departments -> programmes (handy for the dashboard "applications by faculty")
    public function programmes(): HasManyThrough
    {
        return $this->hasManyThrough(Programme::class, Department::class);
    }
}
