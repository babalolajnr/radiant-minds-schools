<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ClassroomSubject extends Pivot
{
    use HasFactory;
    protected $table = 'classroom_subject';
}
