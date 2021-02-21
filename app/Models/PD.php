<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PD extends Model
{
    use HasFactory;
    protected $table = 'p_d_s';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function student()
    {
        $this->belongsTo(Student::class);
    }

    public function academicSession()
    {
        $this->belongsTo(AcademicSession::class);
    }

    public function term()
    {
        $this->belongsTo(Term::class);
    }
}
