<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * AD stands for Affective Domain
 */
class AD extends Model
{
    use HasFactory;
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function period()
    {
       return $this->belongsTo(Period::class);
    }

    public function adType()
    {
        return $this->belongsTo(ADType::class);
    }
}
