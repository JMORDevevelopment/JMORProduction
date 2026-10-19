<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\CheckoutMeta;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class CheckoutMetaPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CheckoutMeta');
    }

    public function view(AuthUser $authUser, CheckoutMeta $checkoutMeta): bool
    {
        return $authUser->can('View:CheckoutMeta');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CheckoutMeta');
    }

    public function update(AuthUser $authUser, CheckoutMeta $checkoutMeta): bool
    {
        return $authUser->can('Update:CheckoutMeta');
    }

    public function delete(AuthUser $authUser, CheckoutMeta $checkoutMeta): bool
    {
        return $authUser->can('Delete:CheckoutMeta');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:CheckoutMeta');
    }

    public function restore(AuthUser $authUser, CheckoutMeta $checkoutMeta): bool
    {
        return $authUser->can('Restore:CheckoutMeta');
    }

    public function forceDelete(AuthUser $authUser, CheckoutMeta $checkoutMeta): bool
    {
        return $authUser->can('ForceDelete:CheckoutMeta');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CheckoutMeta');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CheckoutMeta');
    }

    public function replicate(AuthUser $authUser, CheckoutMeta $checkoutMeta): bool
    {
        return $authUser->can('Replicate:CheckoutMeta');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CheckoutMeta');
    }
}
