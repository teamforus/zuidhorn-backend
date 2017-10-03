<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Office;
use Illuminate\Auth\Access\HandlesAuthorization;

class OfficePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the shopKeeperOffice.
     *
     * @param  \App\User  $user
     * @param  \App\Office  $shopKeeperOffice
     * @return mixed
     */
    public function view(User $user, Office $shopKeeperOffice)
    {
        return true;
    }

    /**
     * Determine whether the user can create shopKeepers.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->permissions()->where(
            'key', 
            'manage_shop-keepers')->count() > 0;
    }

    /**
     * Determine whether the user can update the shopKeeperOffice.
     *
     * @param  \App\User  $user
     * @param  \App\Office  $shopKeeperOffice
     * @return mixed
     */
    public function update(User $user, Office $shopKeeperOffice)
    {
        return $user->permissions()->where(
            'key', 
            'manage_shop-keepers')->count() > 0;
    }

    /**
     * Determine whether the user can delete the shopKeeperOffice.
     *
     * @param  \App\User  $user
     * @param  \App\Office  $shopKeeperOffice
     * @return mixed
     */
    public function delete(User $user, Office $shopKeeperOffice)
    {
        return $user->permissions()->where(
            'key', 
            'manage_shop-keepers')->count() > 0;
    }
}
