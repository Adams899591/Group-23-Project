<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
       protected $guarded = [];

   // ======== Relationship ==========
   public function faculty(){
      return $this->belongsTo(Faculty::class);
   }

 
    public function programmes(): HasMany
    {
        return $this->hasMany(Programme::class);
    }
}
