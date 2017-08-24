<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ShopKeeperOffice;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShopKeeperOfficePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the shopKeeperOffice.
     *
     * @param  \App\User  $user
     * @param  \App\ShopKeeperOffice  $shopKeeperOffice
     * @return mixed
     */
    public function view(User $user, ShopKeeperOffice $shopKeeperOffice)
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
     * @param  \App\ShopKeeperOffice  $shopKeeperOffice
     * @return mixed
     */
    public function update(User $user, ShopKeeperOffice $shopKeeperOffice)
    {
        return $user->permissions()->where(
            'key', 
            'manage_shop-keepers')->count() > 0;
    }

    /**
     * Determine whether the user can delete the shopKeeperOffice.
     *
     * @param  \App\User  $user
     * @param  \App\ShopKeeperOffice  $shopKeeperOffice
     * @return mixed
     */
    public function delete(User $user, ShopKeeperOffice $shopKeeperOffice)
    {
        return $user->permissions()->where(
            'key', 
            'manage_shop-keepers')->count() > 0;
    }
}
