<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PDTypes extends Model
{
    use HasFactory;
    protected $table = 'p_d_types';
    protected $fillable = ['name'];

    public function pds()
    {
        return $this->hasMany(PD::class);
    }
}
