<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\HomeTab;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class HomeTabPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:HomeTab');
    }

    public function view(AuthUser $authUser, HomeTab $homeTab): bool
    {
        return $authUser->can('View:HomeTab');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:HomeTab');
    }

    public function update(AuthUser $authUser, HomeTab $homeTab): bool
    {
        return $authUser->can('Update:HomeTab');
    }

    public function delete(AuthUser $authUser, HomeTab $homeTab): bool
    {
        return $authUser->can('Delete:HomeTab');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:HomeTab');
    }

    public function restore(AuthUser $authUser, HomeTab $homeTab): bool
    {
        return $authUser->can('Restore:HomeTab');
    }

    public function forceDelete(AuthUser $authUser, HomeTab $homeTab): bool
    {
        return $authUser->can('ForceDelete:HomeTab');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:HomeTab');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:HomeTab');
    }

    public function replicate(AuthUser $authUser, HomeTab $homeTab): bool
    {
        return $authUser->can('Replicate:HomeTab');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:HomeTab');
    }
}
