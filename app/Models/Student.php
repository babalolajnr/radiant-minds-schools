<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];
    
    /**
     * Guardian relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function guardian()
    {
        return $this->belongsTo(Guardian::class);
    }
    
    /**
     * Classroom relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
    
    /**
     * Result relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function results()
    {
        return $this->hasMany(Result::class);
    }
    
    /**
     * Pd relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pds()
    {
        return $this->hasMany(PD::class);
    }
    
    /**
     * AD relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ads()
    {
        return $this->hasMany(AD::class);
    }
    
    /**
     * Attendance relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
    
    /**
     * remarks relationship
     *
     * @return void
     */
    public function remarks()
    {
        return $this->hasMany(Remark::class);
    }
     
    /**
     * Find student
     *
     * @param  string $admission_no
     * @return Student $student
     */
    public static function findStudent($admission_no)
    {

        $student = Student::where('admission_no', $admission_no);
        if (!$student->exists()) {
            abort(404);
        }

        return $student;
    }
    
    /**
     * Check if student is active
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->is_active == true;
    }
    
    /**
     * Get all students that have not graduated
     *
     * @return mixed $students
     */
    public static function getAllStudents()
    {
        $students = Student::whereNull('graduated_at')->get();
        return $students;
    }
    
    /**
     * Get Alumni
     *
     * @return mixed $alumni
     */
    public static function getAlumni()
    {
        $alumni = Student::whereNotNull('graduated_at')->get();
        return $alumni;
    }

        
    /**
     * Check if student can graduate
     * 
     * Only Students in the highest class can graduate
     *
     * @return boolean
     */
    public function canGraduate()
    {
        $classRank = $this->classroom->rank;
        $highestClassRank = Classroom::max('rank');

        return $classRank == $highestClassRank;
    }

       
    /**
     * check if student is an alumni
     *
     * @return void
     */
    public function hasGraduated()
    {
        return $this->graduated_at !== null;
    }
}
