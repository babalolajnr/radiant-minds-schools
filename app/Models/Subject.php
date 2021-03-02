<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    public function classrooms()
    {
        return $this->belongsToMany(Classroom::class)->withPivot('academic_session_id')->withTimestamps();
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function assessmentTypes()
    {
        return $this->belongsToMany(AssessmentType::class);
    }
}
