<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the citizen.
     *
     * @param  \App\User  $user
     * @param  \App\User  $citizen
     * @return mixed
     */
    public function view(User $user, User $citizen)
    {
        return ($user->id == $citizen->user_id) || 
        ($user->permissions()->where(
            'key', 
            'manage_citizens')->count() > 0);
    }

    /**
     * Determine whether the user can create citizens.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->permissions()->where(
            'key', 
            'manage_citizens')->count() > 0;
    }

    /**
     * Determine whether the user can update the citizen.
     *
     * @param  \App\User  $user
     * @param  \App\User  $citizen
     * @return mixed
     */
    public function update(User $user, User $citizen)
    {
        return $user->permissions()->where(
            'key', 
            'manage_citizens')->count() > 0;
    }

    /**
     * Determine whether the user can delete the citizen.
     *
     * @param  \App\User  $user
     * @param  \App\User  $citizen
     * @return mixed
     */
    public function delete(User $user, User $citizen)
    {
        return $user->permissions()->where(
            'key', 
            'manage_citizens')->count() > 0;
    }
}
