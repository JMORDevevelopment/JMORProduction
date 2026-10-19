<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\CategoryRadioShow;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class CategoryRadioShowPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CategoryRadioShow');
    }

    public function view(AuthUser $authUser, CategoryRadioShow $categoryRadioShow): bool
    {
        return $authUser->can('View:CategoryRadioShow');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CategoryRadioShow');
    }

    public function update(AuthUser $authUser, CategoryRadioShow $categoryRadioShow): bool
    {
        return $authUser->can('Update:CategoryRadioShow');
    }

    public function delete(AuthUser $authUser, CategoryRadioShow $categoryRadioShow): bool
    {
        return $authUser->can('Delete:CategoryRadioShow');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:CategoryRadioShow');
    }

    public function restore(AuthUser $authUser, CategoryRadioShow $categoryRadioShow): bool
    {
        return $authUser->can('Restore:CategoryRadioShow');
    }

    public function forceDelete(AuthUser $authUser, CategoryRadioShow $categoryRadioShow): bool
    {
        return $authUser->can('ForceDelete:CategoryRadioShow');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CategoryRadioShow');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CategoryRadioShow');
    }

    public function replicate(AuthUser $authUser, CategoryRadioShow $categoryRadioShow): bool
    {
        return $authUser->can('Replicate:CategoryRadioShow');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CategoryRadioShow');
    }
}
