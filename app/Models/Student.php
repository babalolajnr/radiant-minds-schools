<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function guardian()
    {
        return $this->belongsTo(Guardian::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function pds()
    {
        return $this->hasMany(PD::class);
    }

    public function ads()
    {
        return $this->hasMany(AD::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    //check if student exists
    public static function findStudent($admission_no)
    {

        $student = Student::where('admission_no', $admission_no);
        if (!$student->exists()) {
            abort(404);
        }

        return $student;
    }

    public function isActive()
    {
        return $this->is_active == true;
    }

    public static function getAllStudents()
    {
        $students = Student::whereNull('graduated_at')->get();
        return $students;
    }

    public static function getAlumni()
    {
        $alumni = Student::whereNotNull('graduated_at')->get();
        return $alumni;
    }

    //check if student is in the highest class
    public function canGraduate()
    {
        $classRank = $this->classroom->rank;
        $highestClassRank = Classroom::max('rank');

        return $classRank == $highestClassRank;
    }

    // check if student has graduated
    public function hasGraduated()
    {
        return $this->graduated_at !== null;
    }
}
