<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
    ];

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
     * Check if user is an admin
     *
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->user_type == 'admin';
    }
    
    /**
     * Check if user is a master
     *
     * @return boolean
     */
    public function isMaster()
    {
        return $this->user_type == 'master';
    }
    
    /**
     * Check if user is active
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->is_active == true;
    }
    
    /**
     * Check if user is verified
     *
     * @return boolean
     */
    public function isVerified()
    {
        return $this->is_verified == true;
    }
}
