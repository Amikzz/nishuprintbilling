<?php

namespace App\Policies;

use App\Models\PurchaseOrderDatabase;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PurchaseOrderDatabasePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PurchaseOrderDatabase $purchaseOrderDatabase): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PurchaseOrderDatabase $purchaseOrderDatabase): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PurchaseOrderDatabase $purchaseOrderDatabase): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PurchaseOrderDatabase $purchaseOrderDatabase): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PurchaseOrderDatabase $purchaseOrderDatabase): bool
    {
        return false;
    }
}
