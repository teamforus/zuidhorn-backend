<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ShopKeeperCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShopKeeperCategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the shopKeeper.
     *
     * @param  \App\User  $user
     * @param  \App\ShopKeeperCategory  $shopKeeperCategory
     * @return mixed
     */
    public function view(User $user, ShopKeeperCategory $shopKeeperCategory)
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
     * Determine whether the user can update the shopKeeper.
     *
     * @param  \App\User  $user
     * @param  \App\ShopKeeperCategory  $shopKeeperCategory
     * @return mixed
     */
    public function update(User $user, ShopKeeperCategory $shopKeeperCategory)
    {
        return $user->permissions()->where(
            'key', 
            'manage_shop-keepers')->count() > 0;
    }

    /**
     * Determine whether the user can delete the shopKeeper.
     *
     * @param  \App\User  $user
     * @param  \App\ShopKeeperCategory  $shopKeeperCategory
     * @return mixed
     */
    public function delete(User $user, ShopKeeperCategory $shopKeeperCategory)
    {
        return $user->permissions()->where(
            'key', 
            'manage_shop-keepers')->count() > 0;
    }
}
