<?php

namespace App\Policies;

use App\Models\User;
use App\Models\BudgetCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class BudgetCategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the budgetCategory.
     *
     * @param  \App\User  $user
     * @param  \App\BudgetCategory  $budgetCategory
     * @return mixed
     */
    public function view(User $user, BudgetCategory $budgetCategory)
    {
        return true;
    }

    /**
     * Determine whether the user can create budgetCategory.
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
     * Determine whether the user can update the budgetCategory.
     *
     * @param  \App\User  $user
     * @param  \App\BudgetCategory  $budgetCategory
     * @return mixed
     */
    public function update(User $user, BudgetCategory $budgetCategory)
    {
        return $user->permissions()->where(
            'key', 
            'manage_shop-keepers')->count() > 0;
    }

    /**
     * Determine whether the user can delete the budgetCategory.
     *
     * @param  \App\User  $user
     * @param  \App\BudgetCategory  $budgetCategory
     * @return mixed
     */
    public function delete(User $user, BudgetCategory $budgetCategory)
    {
        return $user->permissions()->where(
            'key', 
            'manage_shop-keepers')->count() > 0;
    }
}
