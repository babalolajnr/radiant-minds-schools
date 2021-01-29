<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicSession extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function assessmentResults()
    {
        return $this->hasMany(AssessmentResult::class);
    }
}
