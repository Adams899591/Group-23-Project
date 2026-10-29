<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
   protected $guarded = []; 


   // ======== Relationship ==========
   public function applicant(){
      return $this->belongsTo(Applicant::class);
   }

   public function academicSession(){
      return $this->belongsTo(AcademicSession::class);
   }

   public function programme(){
      return $this->belongsTo(Programme::class);
   }

}
