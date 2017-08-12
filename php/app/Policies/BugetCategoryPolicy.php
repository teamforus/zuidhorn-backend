<?php

namespace App\Policies;

use App\Models\User;
use App\Models\BugetCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class BugetCategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the bugetCategory.
     *
     * @param  \App\User  $user
     * @param  \App\BugetCategory  $bugetCategory
     * @return mixed
     */
    public function view(User $user, BugetCategory $bugetCategory)
    {
        return true;
    }

    /**
     * Determine whether the user can create bugetCategory.
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
     * Determine whether the user can update the bugetCategory.
     *
     * @param  \App\User  $user
     * @param  \App\BugetCategory  $bugetCategory
     * @return mixed
     */
    public function update(User $user, BugetCategory $bugetCategory)
    {
        return $user->permissions()->where(
            'key', 
            'manage_shop-keepers')->count() > 0;
    }

    /**
     * Determine whether the user can delete the bugetCategory.
     *
     * @param  \App\User  $user
     * @param  \App\BugetCategory  $bugetCategory
     * @return mixed
     */
    public function delete(User $user, BugetCategory $bugetCategory)
    {
        return $user->permissions()->where(
            'key', 
            'manage_shop-keepers')->count() > 0;
    }
}
