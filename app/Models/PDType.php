<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PDType extends Model
{
    use HasFactory;
    protected $table = 'p_d_types';
    protected $fillable = ['name', 'slug'];

    public function pds()
    {
        return $this->hasMany(PD::class);
    }
}
