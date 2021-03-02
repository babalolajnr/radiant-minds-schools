<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Student extends Model
{
    use HasFactory;

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

    //check if student exists
    public static function findStudent($admission_no) {

        $student = Student::where('admission_no', $admission_no);
        if (!$student->exists()) {
            abort(404);
        }

        return $student;
    }

}
