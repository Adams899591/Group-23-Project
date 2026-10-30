<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
   protected $guarded = []; 

       // application_date / admission_date become Carbon dates, so you can do $application->application_date->format('d M Y')
    protected $casts = [
        'application_date' => 'date',
        'admission_date'   => 'date',
        'utme_score'       => 'decimal:2',
        'post_utme_score'  => 'decimal:2',
        'aggregate_score'  => 'decimal:2',
    ];


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
