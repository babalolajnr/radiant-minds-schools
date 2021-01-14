<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function classrooms()
    {
        return $this->belongsToMany(Classroom::class);
    }

    public function assessmentResults()
    {
        return $this->hasMany(AssessmentResult::class);
    }
}
