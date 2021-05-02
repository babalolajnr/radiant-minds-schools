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

   protected $table = 'p_d_s';
   protected $guarded = ['id', 'created_at', 'updated_at'];

   public function student()
   {
      return $this->belongsTo(Student::class);
   }

   public function period()
   {
      return $this->belongsTo(Period::class);
   }

   public function pdType()
   {
      return $this->belongsTo(PDType::class);
   }
}
