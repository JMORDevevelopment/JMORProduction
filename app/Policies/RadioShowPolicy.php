<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\RadioShow;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class RadioShowPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:RadioShow');
    }

    public function view(AuthUser $authUser, RadioShow $radioShow): bool
    {
        return $authUser->can('View:RadioShow');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:RadioShow');
    }

    public function update(AuthUser $authUser, RadioShow $radioShow): bool
    {
        return $authUser->can('Update:RadioShow');
    }

    public function delete(AuthUser $authUser, RadioShow $radioShow): bool
    {
        return $authUser->can('Delete:RadioShow');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:RadioShow');
    }

    public function restore(AuthUser $authUser, RadioShow $radioShow): bool
    {
        return $authUser->can('Restore:RadioShow');
    }

    public function forceDelete(AuthUser $authUser, RadioShow $radioShow): bool
    {
        return $authUser->can('ForceDelete:RadioShow');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:RadioShow');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:RadioShow');
    }

    public function replicate(AuthUser $authUser, RadioShow $radioShow): bool
    {
        return $authUser->can('Replicate:RadioShow');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:RadioShow');
    }
}
