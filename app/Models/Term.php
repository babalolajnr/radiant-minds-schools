<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug'];

    public function pds()
    {
        return $this->hasMany(PD::class);
    }

    public function periods()
    {
        return $this->hasMany(Period::class);
    }
}
