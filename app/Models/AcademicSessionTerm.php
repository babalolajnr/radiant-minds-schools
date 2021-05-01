<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * AcademicSessionTerm is also referred to as a 'Period'
 * so wherever period is seen it also means AcademicSessionTerm
 * 
 * This model also serves as the pivot table of 'academic_sessions' and
 * 'terms' table
 */
class AcademicSessionTerm extends Model
{
    use HasFactory;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    protected $table = 'academic_session_term';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function fee()
    {
        return $this->belongsTo(Fee::class);
    }

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class);
    }

    /**
     * checks if period is active
     * @return bool
     */
    public function isActive()
    {
        return $this->active == true;
    }

    /**
     * gets active period
     * @return AcademicSessionTerm
     */
    public static function activePeriod()
    {
        $activePeriod = AcademicSessionTerm::where('active', true)->first();
        return $activePeriod;
    }
}
