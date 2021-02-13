<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicSession extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'start_date', 'end_date'];

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    //get current academic Session
    public static function currentAcademicSession()
    {
        $currentAcademicSession = AcademicSession::where('current_session', 1)->first();
        return $currentAcademicSession;
    }

    public function isCurrentAcademicSession()
    {
        return $this->current_session == 1;
    }
}
