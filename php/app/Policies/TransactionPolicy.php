<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransactionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the voucher_transaction.
     *
     * @param  \App\User  $user
     * @param  \App\Transaction  $voucher_transaction
     * @return mixed
     */
    public function view(User $user, Transaction $voucher_transaction)
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
     * @param  \App\Transaction  $voucher_transaction
     * @return mixed
     */
    public function update(User $user, Transaction $voucher_transaction)
    {
        return $user->permissions()->where(
            'key', 
            'manage_voucher_transactions')->count() > 0;
    }

    /**
     * Determine whether the user can delete the voucher_transaction.
     *
     * @param  \App\User  $user
     * @param  \App\Transaction  $voucher_transaction
     * @return mixed
     */
    public function delete(User $user, Transaction $voucher_transaction)
    {
        return $user->permissions()->where(
            'key', 
            'manage_voucher_transactions')->count() > 0;
    }
}
