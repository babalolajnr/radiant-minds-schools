<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug'];

    public function Results()
    {
        return $this->hasMany(Result::class);
    }

    public function pds()
    {
        return $this->hasMany(PD::class);
    }
}
