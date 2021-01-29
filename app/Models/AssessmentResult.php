<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentResult extends Model
{
    use HasFactory;

    protected $fillable = ['mark', 'term_id', 'subject_id', 'student_id', 'assessment_type_id', 'academic_session_id'];
    
    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function assessmentType()
    {
        return $this->belongsTo(AssessmentType::class);
    }

    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
