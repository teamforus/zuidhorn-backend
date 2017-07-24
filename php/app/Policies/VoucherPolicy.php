<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Voucher;
use Illuminate\Auth\Access\HandlesAuthorization;

class VoucherPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the voucher.
     *
     * @param  \App\User  $user
     * @param  \App\Voucher  $voucher
     * @return mixed
     */
    public function view(User $user, Voucher $voucher)
    {
        return ($user->id == $voucher->user_id) || 
        ($user->permissions()->where(
            'key', 
            'manage_vouchers')->count() > 0);
    }

    /**
     * Determine whether the user can create vouchers.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->permissions()->where(
            'key', 
            'manage_vouchers')->count() > 0;
    }

    /**
     * Determine whether the user can update the voucher.
     *
     * @param  \App\User  $user
     * @param  \App\Voucher  $voucher
     * @return mixed
     */
    public function update(User $user, Voucher $voucher)
    {
        return $user->permissions()->where(
            'key', 
            'manage_vouchers')->count() > 0;
    }

    /**
     * Determine whether the user can delete the voucher.
     *
     * @param  \App\User  $user
     * @param  \App\Voucher  $voucher
     * @return mixed
     */
    public function delete(User $user, Voucher $voucher)
    {
        return $user->permissions()->where(
            'key', 
            'manage_vouchers')->count() > 0;
    }
}
