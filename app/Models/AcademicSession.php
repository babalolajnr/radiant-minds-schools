<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicSession extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'start_date', 'end_date'];

    public function periods()
    {
        return $this->hasMany(Period::class);
    }
}
