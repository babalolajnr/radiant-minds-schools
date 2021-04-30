<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicSessionTerm extends Model
{
    use HasFactory;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    protected $table = 'academic_session_term';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function fee()
    {
        return $this->belongsTo(Fee::class);
    }
}
