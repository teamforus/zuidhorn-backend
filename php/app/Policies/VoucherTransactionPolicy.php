<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VoucherTransaction;
use Illuminate\Auth\Access\HandlesAuthorization;

class VoucherTransactionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the voucher_transaction.
     *
     * @param  \App\User  $user
     * @param  \App\VoucherTransaction  $voucher_transaction
     * @return mixed
     */
    public function view(User $user, VoucherTransaction $voucher_transaction)
    {
        return true;
    }

    /**
     * Determine whether the user can create voucher_transactions.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->permissions()->where(
            'key', 
            'manage_voucher_transactions')->count() > 0;
    }

    /**
     * Determine whether the user can update the voucher_transaction.
     *
     * @param  \App\User  $user
     * @param  \App\VoucherTransaction  $voucher_transaction
     * @return mixed
     */
    public function update(User $user, VoucherTransaction $voucher_transaction)
    {
        return $user->permissions()->where(
            'key', 
            'manage_voucher_transactions')->count() > 0;
    }

    /**
     * Determine whether the user can delete the voucher_transaction.
     *
     * @param  \App\User  $user
     * @param  \App\VoucherTransaction  $voucher_transaction
     * @return mixed
     */
    public function delete(User $user, VoucherTransaction $voucher_transaction)
    {
        return $user->permissions()->where(
            'key', 
            'manage_voucher_transactions')->count() > 0;
    }
}
