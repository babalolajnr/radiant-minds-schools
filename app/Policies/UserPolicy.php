<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->isMaster();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function view(User $user, User $model)
    {
        return $user == $model;
    }

    /**
     * Determine whether the user can update password.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function updatePassword(User $user, User $model)
    {
        return $user == $model;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function update(User $user, User $model)
    {
        return $user == $model;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->isMaster();
    }


    public function verify(User $user)
    {
        return $user->isMaster();
    }

    public function toggleStatus(User $user)
    {
        return $user->isMaster();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function forceDelete(User $user, User $model)
    {
        //
    }

     /**
     * Determine whether the user can store signature
     *
     * @param  User $user
     * @return void
     */
    public function storeSignature(User $user)
    {
        if(auth('teacher')->check()) return false;
        
        return auth('web')->user()->id == $user->id;
    }
}
