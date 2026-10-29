<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
       protected $guarded = [];


       // ======== Relationship ==========

       public function department(){
              return $this->belongsTo(Department::class);
       }

       public function applications(){
              return $this->hasMany(Application::class);
       }
}
