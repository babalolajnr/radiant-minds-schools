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
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'slug' => 'string',
    ];

    /**
     * Fee relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fee()
    {
        return $this->hasMany(Fee::class);
    }

    /**
     * Attendance relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function attendance()
    {
        return $this->hasOne(Attendance::class);
    }

    /**
     * Term relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    /**
     * AcademicSession relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class);
    }

    /**
     * Results relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function results()
    {
        return $this->hasMany(Result::class);
    }

    /**
     * Pds relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pds()
    {
        return $this->hasMany(PD::class);
    }
    
    /**
     * TeacherRemarks relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMan
     */
    public function teacherRemarks()
    {
        return $this->hasMany(TeacherRemark::class);
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
