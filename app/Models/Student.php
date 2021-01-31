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

    public function Results()
    {
        return $this->hasMany(Result::class);
    }

}
