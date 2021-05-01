<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['value', 'academic_session_term_id'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function period()
    {
        return $this->hasOne(AcademicSessionTerm::class);
    }
}
