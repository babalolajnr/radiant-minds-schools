<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    use HasFactory;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    protected $table = 'periods';

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

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function pds()
    {
        return $this->hasMany(PD::class);
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
     * @return Period
     */
    public static function activePeriod()
    {
        $activePeriod = Period::where('active', true)->first();
        return $activePeriod;
    }
}
