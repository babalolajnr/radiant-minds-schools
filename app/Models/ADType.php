<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ADType extends Model
{
    use HasFactory;
    
    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = ['name', 'slug'];
    
    /**
     * casts
     *
     * @var array
     */
    protected $casts = [
        'slug' => 'string'
    ];
    
    /**
     * ads relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ads()
    {
        return $this->hasMany(AD::class);
    }
}
