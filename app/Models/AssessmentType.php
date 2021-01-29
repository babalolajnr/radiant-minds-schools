<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'max_score'];

    public function assessmentResults()
    {
        return $this->hasMany(AssessmentResult::class);
    }
}
