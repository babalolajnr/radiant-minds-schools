<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = ['term_id', 'academic_session_id', 'assessment_type_id'];

    public function assessmentResults()
    {
        return $this->hasMany(AssessmentResult::class);
    }

    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class);
    }

    public function assessmentType()
    {
        return $this->belongsTo(AssessmentType::class);
    }
}
