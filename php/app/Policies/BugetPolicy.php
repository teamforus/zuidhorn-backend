<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Buget;
use Illuminate\Auth\Access\HandlesAuthorization;

class BugetPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the buget.
     *
     * @param  \App\User  $user
     * @param  \App\Buget  $buget
     * @return mixed
     */
    public function view(User $user, Buget $buget)
    {
        return true;
    }

    /**
     * Determine whether the user can create bugets.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->permissions()->where(
            'key', 
            'manage_bugets')->count() > 0;
    }

    /**
     * Determine whether the user can update the buget.
     *
     * @param  \App\User  $user
     * @param  \App\Buget  $buget
     * @return mixed
     */
    public function update(User $user, Buget $buget)
    {
        return $user->permissions()->where(
            'key', 
            'manage_bugets')->count() > 0;
    }

    /**
     * Determine whether the user can delete the buget.
     *
     * @param  \App\User  $user
     * @param  \App\Buget  $buget
     * @return mixed
     */
    public function delete(User $user, Buget $buget)
    {
        return $user->permissions()->where(
            'key', 
            'manage_bugets')->count() > 0;
    }
}
