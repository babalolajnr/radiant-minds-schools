<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * PD stands for Pyschomotor domain
 */
class PD extends Model
{
   use HasFactory;
   
   /**
    * Table asssociated with PD model
    *
    * @var string
    */
   protected $table = 'p_d_s';  

   /**
    * The attributes that are not mass assignable.
    *
    * @var array
    */
   protected $guarded = ['id', 'created_at', 'updated_at'];
   
   /**
    * Student relationship.
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function student()
   {
      return $this->belongsTo(Student::class);
   }
   
   /**
    * Period relationship.
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function period()
   {
      return $this->belongsTo(Period::class);
   }
   
   /**
    * PdType relationship.
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function pdType()
   {
      return $this->belongsTo(PDType::class);
   }
}
