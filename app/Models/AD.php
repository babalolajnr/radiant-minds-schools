<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * AD stands for Affective Domain
 */
class AD extends Model
{
    use HasFactory;    
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];
    
    /**
     * student
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    
    /**
     * period
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function period()
    {
       return $this->belongsTo(Period::class);
    }
    
    /**
     * adType relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function adType()
    {
        return $this->belongsTo(ADType::class);
    }
}
