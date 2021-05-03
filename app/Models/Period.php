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
    
    /**
     * Table associated with Period model
     *
     * @var string
     */
    protected $table = 'periods';
    
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];
    
    /**
     * Fee relationship
     *
     * @return void
     */
    public function fee()
    {
        return $this->belongsTo(Fee::class);
    }
    
    /**
     * Attendance relationship
     *
     * @return void
     */
    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
    
    /**
     * Term relationship
     *
     * @return void
     */
    public function term()
    {
        return $this->belongsTo(Term::class);
    }
    
    /**
     * AcademicSession relationship
     *
     * @return void
     */
    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class);
    }
    
    /**
     * Results relationship
     *
     * @return void
     */
    public function results()
    {
        return $this->hasMany(Result::class);
    }
    
    /**
     * Pds relationship
     *
     * @return void
     */
    public function pds()
    {
        return $this->hasMany(PD::class);
    }

    /**
     * Checks if period is active
     * 
     * @return boolean
     */
    public function isActive()
    {
        return $this->active == true;
    }

    /**
     * Get active period
     * 
     * @return Period $activePeriod
     */
    public static function activePeriod()
    {
        $activePeriod = Period::where('active', true)->first();
        return $activePeriod;
    }
}
