<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicSession extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'start_date', 'end_date'];

    public function results()
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

    public function terms()
    {
        return $this->belongsToMany(Term::class)->using(AcademicSessionTerm::class)->withPivot('start_date', 'end_date')->withTimestamps();
    }

    //get current academic Session
    public static function currentAcademicSession()
    {
        $currentAcademicSession = AcademicSession::where('current_session', 1)->first();
        return $currentAcademicSession;
    }

    public function isCurrentAcademicSession()
    {
        return $this->current_session == 1;
    }
}
