<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function academicSessionTerm()
    {
        return $this->hasOne(AcademicSessionTerm::class);
    }

    public function classroom()
    {
        return $this->hasOne(Classroom::class);
    }

}
