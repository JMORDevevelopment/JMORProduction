<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\MenuGroup;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class MenuGroupPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:MenuGroup');
    }

    public function view(AuthUser $authUser, MenuGroup $menuGroup): bool
    {
        return $authUser->can('View:MenuGroup');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:MenuGroup');
    }

    public function update(AuthUser $authUser, MenuGroup $menuGroup): bool
    {
        return $authUser->can('Update:MenuGroup');
    }

    public function delete(AuthUser $authUser, MenuGroup $menuGroup): bool
    {
        return $authUser->can('Delete:MenuGroup');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:MenuGroup');
    }

    public function restore(AuthUser $authUser, MenuGroup $menuGroup): bool
    {
        return $authUser->can('Restore:MenuGroup');
    }

    public function forceDelete(AuthUser $authUser, MenuGroup $menuGroup): bool
    {
        return $authUser->can('ForceDelete:MenuGroup');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:MenuGroup');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:MenuGroup');
    }

    public function replicate(AuthUser $authUser, MenuGroup $menuGroup): bool
    {
        return $authUser->can('Replicate:MenuGroup');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:MenuGroup');
    }
}
