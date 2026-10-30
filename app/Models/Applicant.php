<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Applicant extends Model
{
      protected $guarded = [];

         // One applicant can have many applications
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }
 
    // $applicant->full_name  ->  "David Chinedu Suleiman" (skips an empty middle name)
    protected function fullName(): Attribute
    {
        return Attribute::get(fn () => collect([$this->first_name, $this->middle_name, $this->last_name])
            ->filter()
            ->implode(' '));
    }
 
    // $applicant->initials  ->  "DS" (used for the avatar circle)
    protected function initials(): Attribute
    {
        return Attribute::get(fn () => strtoupper(
            mb_substr($this->first_name, 0, 1) . mb_substr($this->last_name, 0, 1)
        ));
    }
}
