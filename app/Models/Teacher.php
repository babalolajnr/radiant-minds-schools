<?php

namespace App\Models;

use App\Notifications\TeacherResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword as PasswordsCanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;


class Teacher extends Authenticatable implements CanResetPassword
{
    use HasFactory, SoftDeletes, Notifiable, PasswordsCanResetPassword;

    protected $guard = 'teacher';
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at', 'email_verified_at', 'remember_token'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Classroom relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function classroom()
    {
        return $this->hasOne(Classroom::class);
    }

    /**
     * TeacherRemark relationship
     *
     * @return void
     */
    public function remarks()
    {
        return $this->hasMany(TeacherRemark::class);
    }

    /**
     * Check if teacher is active
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->is_active == true;
    }
    
    /**
     * Send Password reset notification
     *
     * @param  mixed $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $notification = new TeacherResetPassword($token);
        
        $this->notify($notification);
    }
}
