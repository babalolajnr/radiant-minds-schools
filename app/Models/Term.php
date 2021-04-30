<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug'];

    public function Results()
    {
        return $this->hasMany(Result::class);
    }

    public function pds()
    {
        return $this->hasMany(PD::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function academic_sessions()
    {
        return $this->belongsToMany(AcademicSession::class)->withPivot('start_date', 'end_date')->withTimestamps();
    }
}
